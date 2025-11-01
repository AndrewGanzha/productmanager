FROM node:22-alpine

WORKDIR /app

# Копируем package.json из вложенной папки app
COPY app/package*.json ./

RUN npm install

# Копируем весь Vue проект из папки app
COPY app/ .

EXPOSE 3000

CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0"]