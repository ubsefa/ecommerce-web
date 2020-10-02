DROP TABLE IF EXISTS "users";

CREATE TABLE "users"
(
    "id" SERIAL PRIMARY KEY,
    "createdAt" TIMESTAMP,
    "creator" INT,
    "creatorIP" VARCHAR(15),
    "updatedAt" TIMESTAMP,
    "updater" INT,
    "updaterIP" VARCHAR(15),
    "company" VARCHAR(255),
    "identityNumber" VARCHAR(50),
    "photo" VARCHAR(255),
    "nameLastName" VARCHAR(50),
    "userName" VARCHAR(50),
    "email" VARCHAR(255),
    "password" VARCHAR(255),
    "gender" VARCHAR(50),
    "birthDate" TIMESTAMP,
    "address" VARCHAR(255),
    "city" VARCHAR(50),
    "state" VARCHAR(50),
    "postalCode" VARCHAR(50),
    "country" VARCHAR(50),
    "phone" VARCHAR(50),
    "mobile" VARCHAR(50),
    "admin" BIT,
    "roles" VARCHAR(255),
    "active" BIT
);

DROP TABLE IF EXISTS "settings";

CREATE TABLE "settings"
(
    "id" SERIAL PRIMARY KEY,
    "createdAt" TIMESTAMP,
    "creator" INT,
    "creatorIP" VARCHAR(15),
    "updatedAt" TIMESTAMP,
    "updater" INT,
    "updaterIP" VARCHAR(15),
    "title" VARCHAR(255),
    "keywords" VARCHAR(255),
    "description" VARCHAR(255),
    "logo" VARCHAR(255),
    "email" VARCHAR(255),  
    "address" VARCHAR(255),
    "city" VARCHAR(50),
    "state" VARCHAR(50),
    "postalCode" VARCHAR(50),
    "country" VARCHAR(50),
    "phone" VARCHAR(50),
    "features" TEXT,
    "maintenance" BIT
);

DROP TABLE IF EXISTS "addresses";

CREATE TABLE "addresses"
(
    "id" SERIAL PRIMARY KEY,
    "createdAt" TIMESTAMP,
    "creator" INT,
    "creatorIP" VARCHAR(15),
    "updatedAt" TIMESTAMP,
    "updater" INT,
    "updaterIP" VARCHAR(15),
    "title" VARCHAR(255),
    "nameLastName" VARCHAR(255),
    "address" VARCHAR(255),
    "city" VARCHAR(50),
    "state" VARCHAR(50),
    "postalCode" VARCHAR(50),
    "country" VARCHAR(50),
    "phone" VARCHAR(50),
    "active" BIT
);

DROP TABLE IF EXISTS "categories";

CREATE TABLE "categories"
(
    "id" SERIAL PRIMARY KEY,
    "createdAt" TIMESTAMP,
    "creator" INT,
    "creatorIP" VARCHAR(15),
    "updatedAt" TIMESTAMP,
    "updater" INT,
    "updaterIP" VARCHAR(15),
    "parent" INT,
    "title" VARCHAR(255),
    "keywords" VARCHAR(255),
    "description" VARCHAR(255),
    "image" VARCHAR(255),
    "languageCode" VARCHAR(15),
    "active" BIT
);

DROP TABLE IF EXISTS "products";

CREATE TABLE "products"
(
    "id" SERIAL PRIMARY KEY,
    "createdAt" TIMESTAMP,
    "creator" INT,
    "creatorIP" VARCHAR(15),
    "updatedAt" TIMESTAMP,
    "updater" INT,
    "updaterIP" VARCHAR(15),
    "sku" VARCHAR(255),
    "title" VARCHAR(255),
    "keywords" VARCHAR(255),
    "description" VARCHAR(255),
    "price" DECIMAL(5, 2),    
    "tax" DECIMAL(5, 2),
    "discount" DECIMAL(5, 2),
    "image" VARCHAR(255),
    "languageCode" VARCHAR(15),
    "active" BIT
);

DROP TABLE IF EXISTS "comments";

CREATE TABLE "comments"
(
    "id" SERIAL PRIMARY KEY,
    "createdAt" TIMESTAMP,
    "creator" INT,
    "creatorIP" VARCHAR(15),
    "updatedAt" TIMESTAMP,
    "updater" INT,
    "updaterIP" VARCHAR(15),
    "product" INT,
    "comment" VARCHAR(255),
    "active" BIT
);

DROP TABLE IF EXISTS "galleries";

CREATE TABLE "galleries"
(
    "id" SERIAL PRIMARY KEY,
    "createdAt" TIMESTAMP,
    "creator" INT,
    "creatorIP" VARCHAR(15),
    "updatedAt" TIMESTAMP,
    "updater" INT,
    "updaterIP" VARCHAR(15),
    "product" INT,
    "title" VARCHAR(255),
    "description" VARCHAR(255),
    "url" VARCHAR(255),
    "active" BIT
);

DROP TABLE IF EXISTS "orders";

CREATE TABLE "orders"
(
    "id" SERIAL PRIMARY KEY,
    "createdAt" TIMESTAMP,
    "creator" INT,
    "creatorIP" VARCHAR(15),
    "updatedAt" TIMESTAMP,
    "updater" INT,
    "updaterIP" VARCHAR(15),
    "active" BIT
);

DROP TABLE IF EXISTS "carts";

CREATE TABLE "carts"
(
    "id" SERIAL PRIMARY KEY,
    "createdAt" TIMESTAMP,
    "creator" INT,
    "creatorIP" VARCHAR(15),
    "updatedAt" TIMESTAMP,
    "updater" INT,
    "updaterIP" VARCHAR(15),
    "order" INT,
    "product" INT,
    "quantity" DECIMAL(5, 2),
    "price" DECIMAL(5, 2),
    "tax" DECIMAL(5, 2),
    "discount" DECIMAL(5, 2),
    "features" TEXT
);

DROP TABLE IF EXISTS "payments";

CREATE TABLE "payments"
(
    "id" SERIAL PRIMARY KEY,
    "createdAt" TIMESTAMP,
    "creator" INT,
    "creatorIP" VARCHAR(15),
    "updatedAt" TIMESTAMP,
    "updater" INT,
    "updaterIP" VARCHAR(15),
    "order" INT,
    "paymentDate" TIMESTAMP,
    "allowed" BIT
);

DROP TABLE IF EXISTS "shippings";

CREATE TABLE "shippings"
(
    "id" SERIAL PRIMARY KEY,
    "createdAt" TIMESTAMP,
    "creator" INT,
    "creatorIP" VARCHAR(15),
    "updatedAt" TIMESTAMP,
    "updater" INT,
    "updaterIP" VARCHAR(15),
    "title" VARCHAR(255),
    "email" VARCHAR(255),
    "address" VARCHAR(255),
    "city" VARCHAR(50),
    "state" VARCHAR(50),
    "postalCode" VARCHAR(50),
    "country" VARCHAR(50),
    "phone" VARCHAR(50)
);

DROP TABLE IF EXISTS "shipments";

CREATE TABLE "shipments"
(
    "id" SERIAL PRIMARY KEY,
    "createdAt" TIMESTAMP,
    "creator" INT,
    "creatorIP" VARCHAR(15),
    "updatedAt" TIMESTAMP,
    "updater" INT,
    "updaterIP" VARCHAR(15),
    "shipping" INT,
    "cart" INT,
    "trackingNumber" VARCHAR(255),
    "carrier" INT,
    "comments" VARCHAR(255),
    "active" BIT
);

DROP TABLE IF EXISTS "invoices";

CREATE TABLE "invoices"
(
    "id" SERIAL PRIMARY KEY,
    "createdAt" TIMESTAMP,
    "creator" INT,
    "creatorIP" VARCHAR(15),
    "updatedAt" TIMESTAMP,
    "updater" INT,
    "updaterIP" VARCHAR(15),
    "cart" INT,
    "number" VARCHAR(255),
    "active" BIT
);

DROP TABLE IF EXISTS "menus";

CREATE TABLE "menus"
(
    "id" SERIAL PRIMARY KEY,
    "createdAt" TIMESTAMP,
    "creator" INT,
    "creatorIP" VARCHAR(15),
    "updatedAt" TIMESTAMP,
    "updater" INT,
    "updaterIP" VARCHAR(15),
    "parent" INT,
    "position" VARCHAR(15),
    "title" VARCHAR(255),
    "mega" BIT,
    "sort" INT,
    "languageCode" VARCHAR(15),
    "active" BIT
);

DROP TABLE IF EXISTS "contents";

CREATE TABLE "contents"
(
    "id" SERIAL PRIMARY KEY,
    "createdAt" TIMESTAMP,
    "creator" INT,
    "creatorIP" VARCHAR(15),
    "updatedAt" TIMESTAMP,
    "updater" INT,
    "updaterIP" VARCHAR(15),
    "title" VARCHAR(255),
    "abstract" VARCHAR(255),
    "content" TEXT,
    "image" VARCHAR(255),
    "languageCode" VARCHAR(15),
    "active" BIT
);

DROP TABLE IF EXISTS "subscribers";

CREATE TABLE "subscribers"
(
    "id" SERIAL PRIMARY KEY,
    "createdAt" TIMESTAMP,
    "creator" INT,
    "creatorIP" VARCHAR(15),
    "updatedAt" TIMESTAMP,
    "updater" INT,
    "updaterIP" VARCHAR(15),
    "email" VARCHAR(255),
    "active" BIT
);

DROP TABLE IF EXISTS "features";

CREATE TABLE "features"
(
    "id" SERIAL PRIMARY KEY,
    "createdAt" TIMESTAMP,
    "creator" INT,
    "creatorIP" VARCHAR(15),
    "updatedAt" TIMESTAMP,
    "updater" INT,
    "updaterIP" VARCHAR(15),
    "product" INT,
    "purpose" VARCHAR(255),
    "type" VARCHAR(255),
    "position" VARCHAR(255),
    "value" VARCHAR(255)
);

DROP TABLE IF EXISTS "pairs";

CREATE TABLE "pairs"
(
    "id" SERIAL PRIMARY KEY,
    "createdAt" TIMESTAMP,
    "creator" INT,
    "creatorIP" VARCHAR(15),
    "updatedAt" TIMESTAMP,
    "updater" INT,
    "updaterIP" VARCHAR(15),
    "type" VARCHAR(255),
    "object" VARCHAR(255),
    "key" INT,
    "value" VARCHAR(255)
);