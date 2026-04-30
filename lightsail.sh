aws lightsail push-container-image \
  --region eu-west-2 \
  --service-name dnd-service \
  --label laravel \
  --image dnd-laravel:latest

aws lightsail push-container-image \
  --region eu-west-2 \
  --service-name dnd-service \
  --label nuxt \
  --image dnd-nuxt:latest

aws lightsail push-container-image \
  --region eu-west-2 \
  --service-name dnd-service \
  --label nginx \
  --image dnd-nginx:latest
