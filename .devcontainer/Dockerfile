ARG PHP_VERSION=8.1
FROM php:${PHP_VERSION}-cli

# php:8.0 Bullseye: live security mirrors 404; use the image's pinned snapshot instead.
RUN set -eux; \
    use_snapshot=false; \
    if [ -f /etc/apt/sources.list ] && grep -q 'snapshot.debian.org' /etc/apt/sources.list; then \
        sed -i \
            -e 's|^# deb http://snapshot.debian.org|deb http://snapshot.debian.org|' \
            -e 's|^deb http://deb.debian.org|# deb http://deb.debian.org|' \
            /etc/apt/sources.list; \
        use_snapshot=true; \
    fi; \
    groupadd --gid 1000 vscode; \
    useradd --uid 1000 --gid 1000 --create-home --shell /bin/bash vscode; \
    if [ "$use_snapshot" = true ]; then \
        apt-get -o Acquire::Check-Valid-Until=false update; \
    else \
        apt-get update; \
    fi; \
    export DEBIAN_FRONTEND=noninteractive; \
    apt-get install -y --no-install-recommends \
        curl \
        git \
        libicu-dev \
        libonig-dev \
        libxml2-dev \
        libzip-dev \
        unzip \
        zip; \
    docker-php-ext-install -j"$(nproc)" bcmath dom intl mbstring soap xml zip; \
    curl -fsSL https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer; \
    git config --system --add safe.directory '*'; \
    rm -rf /var/lib/apt/lists/*
