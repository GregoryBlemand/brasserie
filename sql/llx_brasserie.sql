create table llx_brasserie
(
  rowid           			integer AUTO_INCREMENT PRIMARY KEY,
  date_cre         			datetime,
  date_maj                  datetime,
  ref             			varchar(80),
  label           			varchar(255) NOT NULL,
  adresse          			varchar(255),
  status               	    integer DEFAULT 0 NOT NULL,
  entity               	    integer DEFAULT 0 NOT NULL,
  fk_user_author   			integer DEFAULT 0 NOT NULL,
  fk_soc        			integer DEFAULT 0 NOT NULL
)ENGINE=innodb;