# Bolton Challenge

## Getting started

**Goal**

To load a list of NFEs from [https://sandbox-api.arquivei.com.br](https://sandbox-api.arquivei.com.br/) and store them in a local database and expose a API that returns the total value of a NFE for a givem access-key (unique for each NFE).

**How to run**

After did cloned and inside of directory:


Create the customized config file:
> cp .env .env.local

Open `.env.local` and update the API credentials:
> X_API_ID=`<put-api-id-here>`
>
> X_API_KEY=`<put-api-key-here>`

Create the customized configurations for docker-compose:
> cp docker-compose-example.yml docker-compose.yml

Check if ports 8080 and 5432 are not in use. If yes, you may to change the ports of services on docker-compose.yml. After run:
> docker-compose up -d

Install dependencies:
> docker-compose exec app composer install

Create database:
> docker-compose exec app bin/console doctrine:migrations:migrate --no-interaction

Make the first sync of NFEs:
> docker-compose exec app bin/console arquivei:sync-nfes

if so far so good, you can access: 
http://localhost:8080/api/tax-invoice/<invoice-access-key>
    
This will give you a json similar to the following:

````json
{
  "accessKey": "123456789987654321",
  "totalValue": 777.99
}
````