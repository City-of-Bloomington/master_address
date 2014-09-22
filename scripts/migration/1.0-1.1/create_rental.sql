---------------------------------------------------------
-- Tables: ordered orderd for data load, foreign_key order
-- for the Rental application
---------------------------------------------------------
-- name
-- prop_status
-- pull_reas
-- zoning_2007
-- zoning
-- registr
-- address2
-- c_types
-- email_logs
-- inspection_types
-- inspections
-- inspectors
-- owner_phones
-- reg_bills
-- reg_paid
-- regid_name
-- rental_authorized
-- rental_image
-- rental_pull_hist
-- rental_structures
-- rental_units
-- rental_updates
-- variances
---------------------------------------------------------



--------------------------------------------------------
--  DDL for Table NAME
--------------------------------------------------------

  CREATE TABLE "RENTAL"."NAME" 
   (	"NAME_NUM" NUMBER(5,0), 
	"NAME" VARCHAR2(50 BYTE), 
	"ADDRESS" VARCHAR2(50 BYTE), 
	"CITY" VARCHAR2(20 BYTE), 
	"STATE" VARCHAR2(2 BYTE), 
	"ZIP" VARCHAR2(10 BYTE), 
	"PHONE_WORK" VARCHAR2(20 BYTE), 
	"PHONE_HOME" VARCHAR2(20 BYTE), 
	"NOTES" VARCHAR2(500 BYTE), 
	"EMAIL" VARCHAR2(70 BYTE)
   ) 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index NAME_NAME_NUM_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "RENTAL"."NAME_NAME_NUM_PK" ON "RENTAL"."NAME" ("NAME_NUM") 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  Constraints for Table NAME
--------------------------------------------------------

  ALTER TABLE "RENTAL"."NAME" MODIFY ("NAME" CONSTRAINT "NAME_NAME_NN" NOT NULL ENABLE);
 
  ALTER TABLE "RENTAL"."NAME" ADD CONSTRAINT "NAME_NAME_NUM_PK" PRIMARY KEY ("NAME_NUM")
  USING INDEX 
  TABLESPACE "USERS"  ENABLE;




--------------------------------------------------------
--  DDL for Table PROP_STATUS
--------------------------------------------------------

  CREATE TABLE "RENTAL"."PROP_STATUS" 
   (	"STATUS" VARCHAR2(1 BYTE), 
	"STATUS_TEXT" VARCHAR2(20 BYTE)
   ) 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index PROP_STATUS_STATUS_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "RENTAL"."PROP_STATUS_STATUS_PK" ON "RENTAL"."PROP_STATUS" ("STATUS") 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  Constraints for Table PROP_STATUS
--------------------------------------------------------

  ALTER TABLE "RENTAL"."PROP_STATUS" ADD CONSTRAINT "PROP_STATUS_STATUS_PK" PRIMARY KEY ("STATUS")
  USING INDEX 
  TABLESPACE "USERS"  ENABLE;




--------------------------------------------------------
--  DDL for Table PULL_REAS
--------------------------------------------------------

  CREATE TABLE "RENTAL"."PULL_REAS" 
   (	"P_REASON" VARCHAR2(5 BYTE), 
	"PULL_TEXT" VARCHAR2(40 BYTE)
   ) 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index PULL_REAS_P_REASON_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "RENTAL"."PULL_REAS_P_REASON_PK" ON "RENTAL"."PULL_REAS" ("P_REASON") 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  Constraints for Table PULL_REAS
--------------------------------------------------------

  ALTER TABLE "RENTAL"."PULL_REAS" MODIFY ("PULL_TEXT" CONSTRAINT "PULL_REAS_PULL_TEXT_NN" NOT NULL ENABLE);
 
  ALTER TABLE "RENTAL"."PULL_REAS" ADD CONSTRAINT "PULL_REAS_P_REASON_PK" PRIMARY KEY ("P_REASON")
  USING INDEX 
  TABLESPACE "USERS"  ENABLE;




--------------------------------------------------------
--  DDL for Table ZONING_2007
--------------------------------------------------------

  CREATE TABLE "RENTAL"."ZONING_2007" 
   (	"ZONED" VARCHAR2(12 BYTE), 
	"ZONE_TEXT" VARCHAR2(70 BYTE)
   ) 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index SYS_C004190
--------------------------------------------------------

  CREATE UNIQUE INDEX "RENTAL"."SYS_C004190" ON "RENTAL"."ZONING_2007" ("ZONED") 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  Constraints for Table ZONING_2007
--------------------------------------------------------

  ALTER TABLE "RENTAL"."ZONING_2007" ADD PRIMARY KEY ("ZONED")
  USING INDEX 
  TABLESPACE "USERS"  ENABLE;




--------------------------------------------------------
--  DDL for Table ZONING
--------------------------------------------------------

  CREATE TABLE "RENTAL"."ZONING" 
   (	"ZONED" VARCHAR2(12 BYTE), 
	"ZONE_TEXT" VARCHAR2(55 BYTE)
   ) 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index ZONING_ZONED_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "RENTAL"."ZONING_ZONED_PK" ON "RENTAL"."ZONING" ("ZONED") 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  Constraints for Table ZONING
--------------------------------------------------------

  ALTER TABLE "RENTAL"."ZONING" ADD CONSTRAINT "ZONING_ZONED_PK" PRIMARY KEY ("ZONED")
  TABLESPACE "USERS"  ENABLE;





--------------------------------------------------------
--  DDL for Table REGISTR
--------------------------------------------------------

  CREATE TABLE "RENTAL"."REGISTR" 
   (	"ID" NUMBER(8,0), 
	"PROPERTY_STATUS" VARCHAR2(1 BYTE), 
	"AGENT" NUMBER(5,0), 
	"REGISTERED_DATE" DATE, 
	"LAST_CYCLE_DATE" DATE, 
	"PERMIT_ISSUED" DATE, 
	"PERMIT_EXPIRES" DATE, 
	"PERMIT_LENGTH" NUMBER(2,0), 
	"PULL_DATE" DATE, 
	"PULL_REASON" VARCHAR2(5 BYTE), 
	"NEW_RENTAL" VARCHAR2(1 BYTE), 
	"ZONING" VARCHAR2(12 BYTE), 
	"GRANDFATHERED" VARCHAR2(1 BYTE), 
	"ANNEXED" VARCHAR2(1 BYTE), 
	"UNITS" NUMBER(3,0), 
	"STRUCTURES" NUMBER(2,0), 
	"BEDROOMS" VARCHAR2(30 BYTE), 
	"OCC_LOAD" VARCHAR2(50 BYTE), 
	"DATE_BILLED" DATE, 
	"DATE_REC" DATE, 
	"NOTES" VARCHAR2(500 BYTE), 
	"CDBG_FUNDING" CHAR(1 BYTE), 
	"ZONING2" VARCHAR2(5 BYTE), 
	"PROP_TYPE" VARCHAR2(14 BYTE), 
	"BATH_COUNT" NUMBER, 
	"NHOOD" VARCHAR2(10 BYTE), 
	"BUILT_DATE" DATE, 
	"INACTIVE" CHAR(1 BYTE), 
	"AFFORDABLE" CHAR(1 BYTE), 
	"BUILDING_TYPE" VARCHAR2(14 BYTE)
   ) 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index REGISTR_ID_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "RENTAL"."REGISTR_ID_PK" ON "RENTAL"."REGISTR" ("ID") 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  Constraints for Table REGISTR
--------------------------------------------------------

  ALTER TABLE "RENTAL"."REGISTR" ADD CONSTRAINT "REGISTR_ANNEXED_CK" CHECK (annexed IN ('Y', 'N')) ENABLE;
 
  ALTER TABLE "RENTAL"."REGISTR" ADD CONSTRAINT "REGISTR_GRANDFATHERED_CK" CHECK (grandfathered IN ('Y', 'N')) ENABLE;
 
  ALTER TABLE "RENTAL"."REGISTR" ADD CONSTRAINT "REGISTR_ID_PK" PRIMARY KEY ("ID")
  USING INDEX 
  TABLESPACE "USERS"  ENABLE;
 
  ALTER TABLE "RENTAL"."REGISTR" ADD CONSTRAINT "REGISTR_NEW_RENTAL_CK" CHECK (new_rental IN ('Y', 'N')) ENABLE;
 
  ALTER TABLE "RENTAL"."REGISTR" MODIFY ("PROPERTY_STATUS" CONSTRAINT "REGISTR_PROPERTY_STATUS_NN" NOT NULL ENABLE);
--------------------------------------------------------
--  Ref Constraints for Table REGISTR
--------------------------------------------------------

  ALTER TABLE "RENTAL"."REGISTR" ADD CONSTRAINT "REGISTR_AGENT_FK" FOREIGN KEY ("AGENT")
	  REFERENCES "RENTAL"."NAME" ("NAME_NUM") ENABLE;
 
  ALTER TABLE "RENTAL"."REGISTR" ADD CONSTRAINT "REGISTR_PROPERTY_STATUS" FOREIGN KEY ("PROPERTY_STATUS")
	  REFERENCES "RENTAL"."PROP_STATUS" ("STATUS") ENABLE;
 
  ALTER TABLE "RENTAL"."REGISTR" ADD CONSTRAINT "REGISTR_PULL_REASON_FK" FOREIGN KEY ("PULL_REASON")
	  REFERENCES "RENTAL"."PULL_REAS" ("P_REASON") ENABLE;
 
  ALTER TABLE "RENTAL"."REGISTR" ADD CONSTRAINT "REGISTR_ZONING_FK" FOREIGN KEY ("ZONING")
	  REFERENCES "RENTAL"."ZONING" ("ZONED") ENABLE;





--------------------------------------------------------
--  DDL for Table ADDRESS2
--------------------------------------------------------

  CREATE TABLE "RENTAL"."ADDRESS2" 
   (	"STREET_NUM" VARCHAR2(8 BYTE), 
	"STREET_DIR" VARCHAR2(2 BYTE), 
	"STREET_NAME" VARCHAR2(20 BYTE), 
	"STREET_TYPE" VARCHAR2(4 BYTE), 
	"POST_DIR" VARCHAR2(2 BYTE), 
	"SUD_TYPE" VARCHAR2(6 BYTE), 
	"SUD_NUM" VARCHAR2(4 BYTE), 
	"INVALID_ADDR" CHAR(1 BYTE), 
	"REGISTR_ID" NUMBER(8,0), 
	"ID" NUMBER(8,0)
   ) 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index SYS_C004038
--------------------------------------------------------

  CREATE UNIQUE INDEX "RENTAL"."SYS_C004038" ON "RENTAL"."ADDRESS2" ("ID") 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  Constraints for Table ADDRESS2
--------------------------------------------------------

  ALTER TABLE "RENTAL"."ADDRESS2" MODIFY ("STREET_NAME" NOT NULL ENABLE);
 
  ALTER TABLE "RENTAL"."ADDRESS2" MODIFY ("ID" NOT NULL ENABLE);
 
  ALTER TABLE "RENTAL"."ADDRESS2" ADD UNIQUE ("ID")
  USING INDEX 
  TABLESPACE "USERS"  ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table ADDRESS2
--------------------------------------------------------

  ALTER TABLE "RENTAL"."ADDRESS2" ADD FOREIGN KEY ("REGISTR_ID")
	  REFERENCES "RENTAL"."REGISTR" ("ID") ENABLE;





--------------------------------------------------------
--  DDL for Table C_TYPES
--------------------------------------------------------

  CREATE TABLE "RENTAL"."C_TYPES" 
   (	"C_TYPE1" NUMBER(4,0), 
	"COMP_DESC" VARCHAR2(80 BYTE), 
	"COMP_CAT" VARCHAR2(2 BYTE)
   ) 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index C_TYPES_C_TYPE1_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "RENTAL"."C_TYPES_C_TYPE1_PK" ON "RENTAL"."C_TYPES" ("C_TYPE1") 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  Constraints for Table C_TYPES
--------------------------------------------------------

  ALTER TABLE "RENTAL"."C_TYPES" ADD CONSTRAINT "C_TYPES_C_TYPE1_PK" PRIMARY KEY ("C_TYPE1")
  USING INDEX 
  TABLESPACE "USERS"  ENABLE;




--------------------------------------------------------
--  DDL for Table EMAIL_LOGS
--------------------------------------------------------

  CREATE TABLE "RENTAL"."EMAIL_LOGS" 
   (	"SEND_DATE" DATE, 
	"TYPE" VARCHAR2(8 BYTE), 
	"USERID" VARCHAR2(8 BYTE)
   ) 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  Constraints for Table EMAIL_LOGS
--------------------------------------------------------

  ALTER TABLE "RENTAL"."EMAIL_LOGS" ADD CONSTRAINT "EMAIL_TYPE_CHK" CHECK (type in ('General','Expire','Other')) ENABLE;




--------------------------------------------------------
--  DDL for Table INSPECTION_TYPES
--------------------------------------------------------

  CREATE TABLE "RENTAL"."INSPECTION_TYPES" 
   (	"INSP_TYPE" VARCHAR2(4 BYTE), 
	"INSP_DESC" VARCHAR2(30 BYTE)
   ) 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index INSPECTION_TYPES_INSP_TYPE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "RENTAL"."INSPECTION_TYPES_INSP_TYPE_PK" ON "RENTAL"."INSPECTION_TYPES" ("INSP_TYPE") 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  Constraints for Table INSPECTION_TYPES
--------------------------------------------------------

  ALTER TABLE "RENTAL"."INSPECTION_TYPES" ADD CONSTRAINT "INSPECTION_TYPES_INSP_TYPE_PK" PRIMARY KEY ("INSP_TYPE")
  USING INDEX 
  TABLESPACE "USERS"  ENABLE;




--------------------------------------------------------
--  DDL for Table INSPECTIONS
--------------------------------------------------------

  CREATE TABLE "RENTAL"."INSPECTIONS" 
   (	"ID" NUMBER(8,0), 
	"INSPECTION_DATE" DATE, 
	"INSPECTION_TYPE" VARCHAR2(4 BYTE), 
	"COMPLIANCE_DATE" DATE, 
	"VIOLATIONS" NUMBER(3,0), 
	"INSPECTED_BY" VARCHAR2(8 BYTE), 
	"INSP_FILE" VARCHAR2(50 BYTE), 
	"COMMENTS" VARCHAR2(100 BYTE), 
	"FOUNDATION" VARCHAR2(20 BYTE), 
	"ATTIC" VARCHAR2(10 BYTE), 
	"ACCESSORY" VARCHAR2(50 BYTE), 
	"STORY_CNT" VARCHAR2(3 BYTE), 
	"HEAT_SRC" VARCHAR2(15 BYTE), 
	"INSP_ID" NUMBER(8,0), 
	"SMOOK_DETECTORS" NUMBER(3,0), 
	"LIFE_SAFETY" NUMBER(3,0)
   ) 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index SYS_C004065
--------------------------------------------------------

  CREATE UNIQUE INDEX "RENTAL"."SYS_C004065" ON "RENTAL"."INSPECTIONS" ("INSP_ID") 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  Constraints for Table INSPECTIONS
--------------------------------------------------------

  ALTER TABLE "RENTAL"."INSPECTIONS" ADD PRIMARY KEY ("INSP_ID")
  USING INDEX 
  TABLESPACE "USERS"  ENABLE;




--------------------------------------------------------
--  DDL for Table INSPECTORS
--------------------------------------------------------

  CREATE TABLE "RENTAL"."INSPECTORS" 
   (	"INITIALS" VARCHAR2(8 BYTE), 
	"NAME" VARCHAR2(30 BYTE), 
	"ACTIVE" CHAR(1 BYTE) DEFAULT 'y'
   ) 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index INSPECTORS_INITIALS_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "RENTAL"."INSPECTORS_INITIALS_PK" ON "RENTAL"."INSPECTORS" ("INITIALS") 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  Constraints for Table INSPECTORS
--------------------------------------------------------

  ALTER TABLE "RENTAL"."INSPECTORS" ADD CONSTRAINT "INSPECTORS_INITIALS_PK" PRIMARY KEY ("INITIALS")
  USING INDEX 
  TABLESPACE "USERS"  ENABLE;




--------------------------------------------------------
--  DDL for Table OWNER_PHONES
--------------------------------------------------------

  CREATE TABLE "RENTAL"."OWNER_PHONES" 
   (	"ID" NUMBER, 
	"NAME_NUM" NUMBER, 
	"PHONE_NUM" VARCHAR2(30 BYTE), 
	"TYPE" VARCHAR2(12 BYTE)
   ) 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index SYS_C0030665
--------------------------------------------------------

  CREATE UNIQUE INDEX "RENTAL"."SYS_C0030665" ON "RENTAL"."OWNER_PHONES" ("ID") 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  Constraints for Table OWNER_PHONES
--------------------------------------------------------

  ALTER TABLE "RENTAL"."OWNER_PHONES" MODIFY ("PHONE_NUM" NOT NULL ENABLE);
 
  ALTER TABLE "RENTAL"."OWNER_PHONES" MODIFY ("TYPE" NOT NULL ENABLE);
 
  ALTER TABLE "RENTAL"."OWNER_PHONES" ADD PRIMARY KEY ("ID")
  USING INDEX 
  TABLESPACE "USERS"  ENABLE;
 
  ALTER TABLE "RENTAL"."OWNER_PHONES" ADD CONSTRAINT "TYPE_ENUM_CHK" CHECK (type in ('Home','Work','Cell','Emergency')) ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table OWNER_PHONES
--------------------------------------------------------

  ALTER TABLE "RENTAL"."OWNER_PHONES" ADD FOREIGN KEY ("NAME_NUM")
	  REFERENCES "RENTAL"."NAME" ("NAME_NUM") ENABLE;



--------------------------------------------------------
--  DDL for Table REG_BILLS
--------------------------------------------------------

  CREATE TABLE "RENTAL"."REG_BILLS" 
   (	"BID" NUMBER, 
	"ID" NUMBER(8,0), 
	"ISSUE_DATE" DATE, 
	"DUE_DATE" DATE, 
	"BUL_RATE" NUMBER, 
	"UNIT_RATE" NUMBER, 
	"BATH_RATE" NUMBER, 
	"REINSP_RATE" NUMBER, 
	"NOSHOW_RATE" NUMBER, 
	"BHQA_FINE" NUMBER, 
	"BUL_CNT" NUMBER(*,0), 
	"UNIT_CNT" NUMBER(*,0), 
	"BATH_CNT" NUMBER(*,2), 
	"NOSHOW_CNT" NUMBER(*,0), 
	"REINSP_CNT" NUMBER(*,0), 
	"REINSP_DATE" VARCHAR2(80 BYTE) DEFAULT null, 
	"NOSHOW_DATE" VARCHAR2(80 BYTE) DEFAULT null, 
	"STATUS" VARCHAR2(6 BYTE), 
	"APPEAL" VARCHAR2(1 BYTE) DEFAULT null, 
	"APPEAL_FEE" NUMBER DEFAULT 0, 
	"CREDIT" NUMBER DEFAULT 0, 
	"SUMMARY_RATE" NUMBER DEFAULT 0, 
	"IDL_RATE" NUMBER DEFAULT 0, 
	"SUMMARY_FLAG" CHAR(1 BYTE) DEFAULT null, 
	"IDL_FLAG" CHAR(1 BYTE) DEFAULT null, 
	"SUMMARY_CNT" NUMBER, 
	"IDL_CNT" NUMBER
   ) 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index SYS_C004177
--------------------------------------------------------

  CREATE UNIQUE INDEX "RENTAL"."SYS_C004177" ON "RENTAL"."REG_BILLS" ("BID") 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  Constraints for Table REG_BILLS
--------------------------------------------------------

  ALTER TABLE "RENTAL"."REG_BILLS" ADD PRIMARY KEY ("BID")
  USING INDEX 
  TABLESPACE "USERS"  ENABLE;




--------------------------------------------------------
--  DDL for Table REG_PAID
--------------------------------------------------------

  CREATE TABLE "RENTAL"."REG_PAID" 
   (	"BID" NUMBER, 
	"REC_SUM" NUMBER, 
	"REC_DATE" DATE, 
	"REC_FROM" VARCHAR2(80 BYTE), 
	"PAID_BY" VARCHAR2(8 BYTE), 
	"CHECK_NO" VARCHAR2(20 BYTE), 
	"RECEIPT_NO" NUMBER DEFAULT 0
   ) 
  TABLESPACE "USERS" ;




--------------------------------------------------------
--  DDL for Table REGID_NAME
--------------------------------------------------------

  CREATE TABLE "RENTAL"."REGID_NAME" 
   (	"NAME_NUM" NUMBER(5,0), 
	"ID" NUMBER(8,0)
   ) 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index REGID_NAME_NAME_NUM_ID_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "RENTAL"."REGID_NAME_NAME_NUM_ID_PK" ON "RENTAL"."REGID_NAME" ("NAME_NUM", "ID") 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  Constraints for Table REGID_NAME
--------------------------------------------------------

  ALTER TABLE "RENTAL"."REGID_NAME" ADD CONSTRAINT "REGID_NAME_NAME_NUM_ID_PK" PRIMARY KEY ("NAME_NUM", "ID")
  USING INDEX 
  TABLESPACE "USERS"  ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table REGID_NAME
--------------------------------------------------------

  ALTER TABLE "RENTAL"."REGID_NAME" ADD CONSTRAINT "REGID_NAME_ID_FK" FOREIGN KEY ("ID")
	  REFERENCES "RENTAL"."REGISTR" ("ID") ENABLE;
 
  ALTER TABLE "RENTAL"."REGID_NAME" ADD CONSTRAINT "REGID_NAME_NAME_NUM_FK" FOREIGN KEY ("NAME_NUM")
	  REFERENCES "RENTAL"."NAME" ("NAME_NUM") ENABLE;




--------------------------------------------------------
--  DDL for Table RENTAL_AUTHORIZED
--------------------------------------------------------

  CREATE TABLE "RENTAL"."RENTAL_AUTHORIZED" 
   (	"USERID" VARCHAR2(8 BYTE), 
	"FULL_NAME" VARCHAR2(30 BYTE) DEFAULT null, 
	"ACCESSLEVEL" NUMBER(1,0) DEFAULT 1, 
	"ROLE" VARCHAR2(30 BYTE) DEFAULT null
   ) 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  Constraints for Table RENTAL_AUTHORIZED
--------------------------------------------------------

  ALTER TABLE "RENTAL"."RENTAL_AUTHORIZED" MODIFY ("USERID" NOT NULL ENABLE);




--------------------------------------------------------
--  DDL for Table RENTAL_IMAGE
--------------------------------------------------------

  CREATE TABLE "RENTAL"."RENTAL_IMAGE" 
   (	"IDIM" NUMBER(8,0), 
	"IMAGE_DATE" DATE, 
	"IMAGE_FILE" VARCHAR2(30 BYTE), 
	"NOTES" VARCHAR2(500 BYTE)
   ) 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  Constraints for Table RENTAL_IMAGE
--------------------------------------------------------

  ALTER TABLE "RENTAL"."RENTAL_IMAGE" MODIFY ("IMAGE_FILE" NOT NULL ENABLE);




--------------------------------------------------------
--  DDL for Table RENTAL_PULL_HIST
--------------------------------------------------------

  CREATE TABLE "RENTAL"."RENTAL_PULL_HIST" 
   (	"ID" NUMBER, 
	"PULL_DATE" DATE, 
	"PULL_REASON" VARCHAR2(2 BYTE)
   ) 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index REGISTR_PULL_DAILY_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "RENTAL"."REGISTR_PULL_DAILY_PK" ON "RENTAL"."RENTAL_PULL_HIST" ("ID", "PULL_DATE", "PULL_REASON") 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  Constraints for Table RENTAL_PULL_HIST
--------------------------------------------------------

  ALTER TABLE "RENTAL"."RENTAL_PULL_HIST" ADD CONSTRAINT "REGISTR_PULL_DAILY_PK" PRIMARY KEY ("ID", "PULL_DATE", "PULL_REASON")
  USING INDEX 
  TABLESPACE "USERS"  ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table RENTAL_PULL_HIST
--------------------------------------------------------

  ALTER TABLE "RENTAL"."RENTAL_PULL_HIST" ADD CONSTRAINT "REGISTR_PRIM_KEY_FK" FOREIGN KEY ("ID")
	  REFERENCES "RENTAL"."REGISTR" ("ID") ENABLE;
 
  ALTER TABLE "RENTAL"."RENTAL_PULL_HIST" ADD CONSTRAINT "REGISTR_PULL_REASON2_FK" FOREIGN KEY ("PULL_REASON")
	  REFERENCES "RENTAL"."PULL_REAS" ("P_REASON") ENABLE;

--------------------------------------------------------
--  DDL for Table RENTAL_STRUCTURES
--------------------------------------------------------

  CREATE TABLE "RENTAL"."RENTAL_STRUCTURES" 
   (	"ID" NUMBER(*,0), 
	"RID" NUMBER(*,0), 
	"IDENTIFIER" VARCHAR2(30 BYTE)
   ) 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index SYS_C0016404
--------------------------------------------------------

  CREATE UNIQUE INDEX "RENTAL"."SYS_C0016404" ON "RENTAL"."RENTAL_STRUCTURES" ("ID") 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  Constraints for Table RENTAL_STRUCTURES
--------------------------------------------------------

  ALTER TABLE "RENTAL"."RENTAL_STRUCTURES" MODIFY ("RID" NOT NULL ENABLE);
 
  ALTER TABLE "RENTAL"."RENTAL_STRUCTURES" MODIFY ("IDENTIFIER" NOT NULL ENABLE);
 
  ALTER TABLE "RENTAL"."RENTAL_STRUCTURES" ADD PRIMARY KEY ("ID")
  USING INDEX 
  TABLESPACE "USERS"  ENABLE;




--------------------------------------------------------
--  DDL for Table RENTAL_UNITS
--------------------------------------------------------

  CREATE TABLE "RENTAL"."RENTAL_UNITS" 
   (	"ID" NUMBER(*,0), 
	"SID" NUMBER(*,0), 
	"UNITS" NUMBER(*,0), 
	"BEDROOMS" NUMBER(*,0), 
	"OCCLOAD" NUMBER(*,0), 
	"SLEEPROOM" CHAR(1 BYTE) DEFAULT null, 
	"UNINSPECTED" CHAR(1 BYTE) DEFAULT null
   ) 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index SYS_C0036947
--------------------------------------------------------

  CREATE UNIQUE INDEX "RENTAL"."SYS_C0036947" ON "RENTAL"."RENTAL_UNITS" ("ID") 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  Constraints for Table RENTAL_UNITS
--------------------------------------------------------

  ALTER TABLE "RENTAL"."RENTAL_UNITS" MODIFY ("SID" NOT NULL ENABLE);
 
  ALTER TABLE "RENTAL"."RENTAL_UNITS" MODIFY ("UNITS" NOT NULL ENABLE);
 
  ALTER TABLE "RENTAL"."RENTAL_UNITS" MODIFY ("BEDROOMS" NOT NULL ENABLE);
 
  ALTER TABLE "RENTAL"."RENTAL_UNITS" MODIFY ("OCCLOAD" NOT NULL ENABLE);
 
  ALTER TABLE "RENTAL"."RENTAL_UNITS" ADD PRIMARY KEY ("ID")
  USING INDEX 
  TABLESPACE "USERS"  ENABLE;




--------------------------------------------------------
--  DDL for Table RENTAL_UPDATES
--------------------------------------------------------

  CREATE TABLE "RENTAL"."RENTAL_UPDATES" 
   (	"REG_ID" NUMBER(8,0), 
	"ACTION" CHAR(1 BYTE), 
	"EVENT_DATE" DATE
   ) 
  TABLESPACE "USERS" ;




--------------------------------------------------------
--  DDL for Table VARIANCES
--------------------------------------------------------

  CREATE TABLE "RENTAL"."VARIANCES" 
   (	"VARIANCE_DATE" DATE, 
	"VARIANCE" varchar2(4000), 
	"ID" NUMBER(8,0), 
	"VID" NUMBER(8,0)
   ) 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index SYS_C0038840
--------------------------------------------------------

  CREATE UNIQUE INDEX "RENTAL"."SYS_C0038840" ON "RENTAL"."VARIANCES" ("VID") 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  Constraints for Table VARIANCES
--------------------------------------------------------

  ALTER TABLE "RENTAL"."VARIANCES" ADD PRIMARY KEY ("VID")
  USING INDEX 
  TABLESPACE "USERS"  ENABLE;








