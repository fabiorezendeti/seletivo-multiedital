#!/usr/bin/env bash
SCRIPTPATH="$( cd "$(dirname "$0")" >/dev/null 2>&1 ; pwd -P )"

echo "Copiando as variáveis dos containers"
cp $SCRIPTPATH/.env.dev.example $SCRIPTPATH/../.env

echo "Criando o arquivo .env do software do candidato"
cp $SCRIPTPATH/../src-candidato/.env.example $SCRIPTPATH/../src-candidato/.env

echo "Pegando o seu UID"
UID=$(id -u)

echo "Seu UID é $UID"

echo "Copiando o UID para o arquivo de ambiente"
sed -i 's@YOUR_UID_HERE@'"`(id -u)`"'@g' $SCRIPTPATH/../.env

echo "Gerando o Build dos containers"
cd $SCRIPTPATH/../
docker-compose build

echo "Subindo os containers"
docker-compose run candidato composer install

echo "Gerando a APP_KEY"
docker-compose run candidato php artisan key:generate


echo "Removendo os containers"
docker-compose down