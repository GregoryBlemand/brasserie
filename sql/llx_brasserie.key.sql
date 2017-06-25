ALTER TABLE llx_brasserie ADD INDEX idx_brasserie_date_cre (date_cre);
ALTER TABLE llx_brasserie ADD INDEX idx_brasserie_date_maj (date_maj);
ALTER TABLE llx_brasserie ADD INDEX idx_brasserie_ref (ref);
ALTER TABLE llx_brasserie ADD INDEX idx_brasserie_entity (entity);
ALTER TABLE llx_brasserie ADD INDEX idx_brasserie_fk_user_author (fk_user_author);
ALTER TABLE llx_brasserie ADD INDEX idx_brasserie_fk_soc (fk_soc);
