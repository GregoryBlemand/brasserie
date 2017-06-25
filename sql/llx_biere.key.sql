ALTER TABLE llx_biere ADD INDEX idx_biere_date_cre (date_cre);
ALTER TABLE llx_biere ADD INDEX idx_biere_date_maj (date_maj);
ALTER TABLE llx_biere ADD INDEX idx_biere_ref (ref);
ALTER TABLE llx_biere ADD INDEX idx_biere_fk_brasserie (fk_brasserie);
