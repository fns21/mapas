docker build . -f ./.nixpacks/Dockerfile -t f221462a-2651-4673-8b92-64aaa0da5e22 --build-arg NIXPACKS_METADATA=php --build-arg NIXPACKS_PHP_FALLBACK_PATH=/app/public/index.php --build-arg NIXPACKS_PHP_ROOT_DIR=/app/public --build-arg PORT=80