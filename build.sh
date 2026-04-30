#!/usr/bin/env bash

set -euo pipefail

REGION="eu-west-2"
SERVICE_NAME="dnd-service"

LARAVEL_IMAGE="dnd-laravel:latest"
NUXT_IMAGE="dnd-nuxt:latest"
NGINX_IMAGE="dnd-nginx:latest"

echo "Building linux/amd64 images..."

docker buildx build \
    --platform linux/amd64 \
    -f .docker/production/laravel/Dockerfile \
    -t ${LARAVEL_IMAGE} \
    --load \
    .

docker buildx build \
    --platform linux/amd64 \
    -f .docker/production/nuxt/Dockerfile \
    -t ${NUXT_IMAGE} \
    --load \
    .

docker buildx build \
    --platform linux/amd64 \
    -f .docker/production/nginx/Dockerfile \
    -t ${NGINX_IMAGE} \
    --load \
    .

echo "Pushing images to Lightsail..."

LARAVEL_LS_IMAGE=$(
    aws lightsail push-container-image \
      --region ${REGION} \
      --service-name ${SERVICE_NAME} \
      --label laravel \
      --image ${LARAVEL_IMAGE} \
      --output json \
    | jq -r '.image'
)

NUXT_LS_IMAGE=$(
    aws lightsail push-container-image \
      --region ${REGION} \
      --service-name ${SERVICE_NAME} \
      --label nuxt \
      --image ${NUXT_IMAGE} \
      --output json \
    | jq -r '.image'
)

NGINX_LS_IMAGE=$(
    aws lightsail push-container-image \
      --region ${REGION} \
      --service-name ${SERVICE_NAME} \
      --label nginx \
      --image ${NGINX_IMAGE} \
      --output json \
    | jq -r '.image'
)

echo "Pushed images:"
echo "Laravel: ${LARAVEL_LS_IMAGE}"
echo "Nuxt: ${NUXT_LS_IMAGE}"
echo "Nginx: ${NGINX_LS_IMAGE}"

echo "Generating deployment file..."

cat > /tmp/lightsail-containers.json <<EOF
{
  "containers": {
    "nginx": {
      "image": "${NGINX_LS_IMAGE}",
      "ports": {
        "80": "HTTP"
      }
    },
    "laravel": {
      "image": "${LARAVEL_LS_IMAGE}",
      "environment": {
        "APP_NAME": "D&D Site",
        "APP_ENV": "production",
        "APP_DEBUG": "false",
        "APP_URL": "https://api.fw190a8.com",

        "DB_CONNECTION": "mysql",
        "DB_HOST": "ls-6c97905df9717f1a708bfe0ccf0102d24e8fcfd3.chocucoqobuq.eu-west-2.rds.amazonaws.com",
        "DB_PORT": "3306",
        "DB_DATABASE": "dnd",
        "DB_USERNAME": "${DB_USERNAME}",
        "DB_PASSWORD": "${DB_PASSWORD}",

        "CACHE_STORE": "database",
        "QUEUE_CONNECTION": "database",
        "SESSION_DRIVER": "database",

        "SESSION_DOMAIN": ".fw190a8.com",
        "SANCTUM_STATEFUL_DOMAINS": "fw190a8.com,www.fw190a8.com"
      },
      "ports": {
        "9000": "TCP"
      }
    },
    "nuxt": {
      "image": "${NUXT_LS_IMAGE}",
      "environment": {
        "NODE_ENV": "production",
        "HOST": "0.0.0.0",
        "PORT": "3000",
        "NUXT_PUBLIC_API_URL": "https://api.fw190a8.com/api",
        "NUXT_PUBLIC_CDN_URL": "https://dnd001.s3.eu-west-2.amazonaws.com",
        "NUXT_PUBLIC_SITE_NAME": "Everything D&D"
      },
      "ports": {
        "3000": "HTTP"
      }
    }
  },
  "publicEndpoint": {
    "containerName": "nginx",
    "containerPort": 80,
    "healthCheck": {
      "path": "/",
      "successCodes": "200-399"
    }
  }
}
EOF

echo "Deploying to Lightsail..."

aws lightsail create-container-service-deployment \
  --region "${REGION}" \
  --service-name "${SERVICE_NAME}" \
  --cli-input-json file:///tmp/lightsail-containers.json

echo "Deployment started."
