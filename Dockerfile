FROM debian:12.12

RUN apt-get update && apt-get install -y \
    # debian:12.10 => Bookworm 12 => Php 8.2 (https://wiki.debian.org/PHP)
    php8.2 php8.2-cli php8.2-xml php8.2-mysql php8.2-mbstring php8.2-intl \
    # For composer
    zip && \
    rm -rf /var/lib/apt/lists/* && \
    apt-get purge -y --auto-remove && \
    apt-get autoremove && \
    apt-get clean;

# For Composer commands (composer install, etc.)
COPY --from=composer:2.2.25 /usr/bin/composer /usr/bin/composer

ARG USER_NAME
ARG USER_ID
ARG GROUP_ID

RUN addgroup ${USER_NAME} --gid ${GROUP_ID}; \
    useradd ${USER_NAME} --uid ${USER_ID} --gid ${GROUP_ID} --create-home;

# Comment this line and rebuild if you need root access
USER "${USER_NAME}"

WORKDIR /var/www/
