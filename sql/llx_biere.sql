create table llx_biere
(
  rowid           			integer AUTO_INCREMENT PRIMARY KEY,
  date_cre         			datetime,
  date_maj                  datetime,
  ref             			varchar(80),
  label           			varchar(255) NOT NULL,
  prix                	    float DEFAULT 0,
  fk_brasserie    			integer DEFAULT NULL
)ENGINE=innodb;