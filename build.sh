docker buildx build \
    --platform linux/amd64 \
    -f .docker/production/laravel/Dockerfile \
    -t dnd-laravel:latest \
    --load \
    .

docker buildx build \
    --platform linux/amd64 \
    -f .docker/production/nuxt/Dockerfile \
    -t dnd-nuxt:latest \
    --load \
    .

docker buildx build \
    --platform linux/amd64 \
    -f .docker/nginx/laravel/Dockerfile \
    -t dnd-nginx:latest \
    --load \
    .
