FROM debian:12.12

RUN apt-get update && apt-get install -y \
    # debian:12.10 => Bookworm 12 => Php 8.2 (https://wiki.debian.org/PHP)
    php8.2 php8.2-cli php8.2-xml php8.2-pgsql php8.2-mbstring php8.2-intl \
    # For composer
    zip \
    wget \
    && rm -rf /var/lib/apt/lists/* && \
    apt-get purge -y --auto-remove && \
    apt-get autoremove && \
    apt-get clean;

# For Composer commands (composer install, etc.)
COPY --from=composer:2.2.25 /usr/bin/composer /usr/bin/composer

# For Symfony commands (symfony check:requirements, etc.)
RUN wget https://get.symfony.com/cli/installer -O - | bash && \
    mv /root/.symfony5/bin/symfony /usr/local/bin/symfony;

ARG USER_NAME
ARG USER_ID
ARG GROUP_ID

RUN addgroup ${USER_NAME} --gid ${GROUP_ID}; \
    useradd ${USER_NAME} --uid ${USER_ID} --gid ${GROUP_ID} --create-home;

# Comment this line and rebuild if you need root access
USER "${USER_NAME}"

WORKDIR /var/www/
