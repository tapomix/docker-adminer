FROM alpine:latest

ARG USER=adminer
ARG UID=1000
ARG GID=${UID}

# Create user with specific UID/GID for rootless compatibility
# Set home directory to /app
RUN addgroup -g ${GID} ${USER} \
    && adduser -D -h /app -u ${UID} -G ${USER} ${USER}

# Install PHP + database extensions (pre-compiled packages)
RUN apk add --no-cache \
        php84 \
        php84-session \
        php84-pdo_mysql \
        php84-pdo_pgsql \
        php84-pdo_sqlite

# Copy custom PHP config
COPY .docker/custom-php.ini /etc/php84/conf.d/20-adminer.ini

WORKDIR /app

# Copy PHP files and config
COPY --chown=${UID}:${GID} src .

USER ${USER}

ARG ADMINER_VERSION=5.4.1
ARG ADMINER_FLAVOUR=-en
# @see https://github.com/vrana/adminer/releases
ARG ADMINER_HASH_PHP=85d70270c469a4a7ff6f636d86bc714e1dfc603935e73c455aab7cafa6a4f0cb
ARG ADMINER_HASH_SRC=f8a8b8ea647e13cd85feca54d0304cee459e958df303d6dd15a778e07799e4ba

ARG TMP_DIR=/tmp
ARG TMP_SRC_FILE=${TMP_DIR}/adminer-source.zip
ARG TMP_EXTRACT_DIR=${TMP_DIR}/adminer-${ADMINER_VERSION}

RUN \
    # Download adminer flavoured file
    wget -q https://github.com/vrana/adminer/releases/download/v${ADMINER_VERSION}/adminer-${ADMINER_VERSION}${ADMINER_FLAVOUR}.php -O adminer.php \
    # Check hash
    && echo "${ADMINER_HASH_PHP}  adminer.php" | sha256sum -c - \
    # Download sources to load designs + plugins
    && wget -q https://github.com/vrana/adminer/releases/download/v${ADMINER_VERSION}/adminer-${ADMINER_VERSION}.zip -O ${TMP_SRC_FILE} \
    # Check hash
    && echo "${ADMINER_HASH_SRC}  ${TMP_SRC_FILE}" | sha256sum -c - \
    # Unzip sources in tmp dir
    && unzip -q ${TMP_SRC_FILE} -d ${TMP_DIR} \
    # Extract designs + plugins
    && mv ${TMP_EXTRACT_DIR}/designs ./designs \
    && mv ${TMP_EXTRACT_DIR}/plugins ./plugins \
    # Clear tmp files
    && rm -rf ${TMP_SRC_FILE} ${TMP_EXTRACT_DIR}

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "/app/public", "/app/public/index.php"]

