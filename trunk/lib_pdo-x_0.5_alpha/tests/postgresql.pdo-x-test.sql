CREATE DATABASE "pdo-x-test";

CREATE TABLE "public"."test" (
  "id" INTEGER NOT NULL, 
  "name" VARCHAR, 
  "date" DATE, 
  "number" INTEGER, 
  PRIMARY KEY("id")
) WITH OIDS;

CREATE SEQUENCE "public"."test_id_seq"
    INCREMENT 1  MINVALUE 1
    START 1;

ALTER TABLE "public"."test"
  ALTER COLUMN "id" SET DEFAULT nextval('test_id_seq'::regclass);

ALTER TABLE "public"."test"
  ALTER COLUMN "date" SET DEFAULT now();

ALTER TABLE "public"."test"
  ALTER COLUMN "name" SET NOT NULL;

INSERT INTO test (name, date, number) VALUES ('John', now(), 12345);
INSERT INTO test (name, date, number) VALUES ('Jane', now(), 54321);