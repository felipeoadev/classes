/* CONSULTA OS CAMPOS DE UMA TABELA MySQL */
SELECT COLUMN_NAME AS Campos, IF(COLUMN_KEY = 'PRI', 'S', 'N') AS Primary_Key
FROM information_schema.`COLUMNS` 
WHERE TABLE_SCHEMA = 'gymup' AND 
      TABLE_NAME = 'pessoa';
      
      
SELECT *
FROM information_schema.`COLUMNS` 
WHERE TABLE_SCHEMA = 'gymup' AND 
      TABLE_NAME = 'pessoa';      


