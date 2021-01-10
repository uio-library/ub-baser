CREATE VIEW oversatte_lover_view AS
SELECT oversettelse.*
       , lov.tittel AS lov_tittel
       , lov.kort_tittel AS lov_kort_tittel
       , lov.note AS lov_note
       , lov.dok_type AS lov_dok_type
       , lov.dato AS lov_dato
       , lov.nummer AS lov_nummer

  FROM oversatte_lover_oversettelser AS oversettelse
       JOIN oversatte_lover_lover AS lov
       ON oversettelse.lov_id = lov.id
