CREATE DATABASE "pdo-x-example";

CREATE TABLE "public"."person" (
  "id" INTEGER NOT NULL, 
  "name" VARCHAR(100), 
  "phone_number" VARCHAR(100), 
  "email" VARCHAR(100), 
  PRIMARY KEY("id")
) WITH OIDS;

CREATE SEQUENCE "public"."person_id_seq"
    INCREMENT 1  MINVALUE 1
    START 1;

ALTER TABLE "public"."person"
  ALTER COLUMN "id" SET DEFAULT nextval('person_id_seq'::regclass);

CREATE TABLE "public"."groups" (
  "id" INTEGER NOT NULL, 
  "name" VARCHAR(100), 
  PRIMARY KEY("id")
) WITH OIDS;

CREATE SEQUENCE "public"."groups_id_seq"
    INCREMENT 1  MINVALUE 1
    START 1;

ALTER TABLE "public"."groups"
  ALTER COLUMN "id" SET DEFAULT nextval('groups_id_seq'::regclass);

CREATE TABLE "public"."person_groups" (
  "id" INTEGER NOT NULL, 
  "person_id" INTEGER NOT NULL, 
  "group_id" INTEGER NOT NULL, 
  PRIMARY KEY("id")
) WITH OIDS;

CREATE SEQUENCE "public"."person_groups_id_seq"
    INCREMENT 1  MINVALUE 1
    START 1;

ALTER TABLE "public"."person_groups"
  ALTER COLUMN "id" SET DEFAULT nextval('person_groups_id_seq'::regclass);

ALTER TABLE "public"."person_groups"
  ADD CONSTRAINT "person_groups_fk" FOREIGN KEY ("person_id")
    REFERENCES "public"."person"("id")
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

ALTER TABLE "public"."person_groups"
  ADD CONSTRAINT "person_groups_fk1" FOREIGN KEY ("group_id")
    REFERENCES "public"."groups"("id")
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;
