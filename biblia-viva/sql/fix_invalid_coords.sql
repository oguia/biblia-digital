-- Remove coordenadas inválidas (0,0) que apontam para o mar (Golfo da Guiné)
DELETE FROM contexto_geografico
WHERE (latitude = 0 AND longitude = 0)
   OR (latitude IS NULL AND longitude IS NULL);

-- Opcional: Remover coordenadas muito próximas de 0 (margem de erro)
DELETE FROM contexto_geografico
WHERE ABS(latitude) < 0.0001 AND ABS(longitude) < 0.0001;
