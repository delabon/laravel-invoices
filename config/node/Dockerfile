FROM node:22.15.1-slim

ARG UID=1000
ARG GID=1000

WORKDIR /code

RUN groupmod -g "${GID}" node && usermod -u "${UID}" -g "${GID}" node

USER node
