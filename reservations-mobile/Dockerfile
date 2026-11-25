# Expo en modo web, para desarrollo en navegador
FROM node:22-bookworm-slim

ENV EXPO_NO_TELEMETRY=1 CI=1
WORKDIR /app
RUN chown node:node /app
USER node

COPY --chown=node:node package.json package-lock.json ./
RUN npm ci --no-audit --no-fund

COPY --chown=node:node . .

EXPOSE 8081
CMD ["npx", "expo", "start", "--web", "--port", "8081"]
