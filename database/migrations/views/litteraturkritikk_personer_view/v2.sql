CREATE VIEW litteraturkritikk_personer_view AS
SELECT
    p.*,

    -- Fullt navn med etternavn først (invertert)
    (CASE
        -- Etternavn, Fornavn (Født-Død)
         WHEN p.fornavn IS NOT NULL AND p.etternavn IS NOT NULL AND p.fodt IS NOT NULL AND p.dod IS NOT NULL
             THEN p.etternavn::text || ', '::text || p.fornavn::text || ', '::text || p.fodt::text || '-'::text || p.dod::text

        -- Etternavn, Fornavn (Født-)
         WHEN p.fornavn IS NOT NULL AND p.etternavn IS NOT NULL AND p.fodt IS NOT NULL
             THEN p.etternavn::text || ', '::text || p.fornavn::text || ', '::text || p.fodt::text || '-'::text

        -- Etternavn, Fornavn
         WHEN p.fornavn IS NOT NULL AND p.etternavn IS NOT NULL
             THEN p.etternavn::text || ', '::text || p.fornavn::text

        -- Etternavn
         WHEN p.etternavn IS NOT NULL
             THEN p.etternavn

         ELSE ''
        END) AS etternavn_fornavn,

    -- Fullt navn med fornavn først, uten dato
    (CASE
        -- Etternavn, Fornavn
         WHEN p.fornavn IS NOT NULL AND p.etternavn IS NOT NULL
             THEN p.fornavn::text || ' '::text || p.etternavn::text

        -- Etternavn
         WHEN p.etternavn IS NOT NULL
             THEN p.etternavn

         ELSE ''
        END) AS fornavn_etternavn,

    -- Roller
    -- Why array_remove? See https://stackoverflow.com/a/33145722/489916
    ARRAY_REMOVE(ARRAY_AGG(DISTINCT role_n), NULL)
             AS roller,

    -- Søkeindeks 'any_field_ts'.
    -- Vi legger til navn begge veier for å støtte autocomplete med frasematch begge veier.
    TO_TSVECTOR('simple', COALESCE(p.etternavn, ''))
        || TO_TSVECTOR('simple', COALESCE(p.fornavn, ''))
        || TO_TSVECTOR('simple', COALESCE(p.fodt::text, ''))
        || TO_TSVECTOR('simple', COALESCE(p.dod::text, ''))
        || TO_TSVECTOR('simple', COALESCE(p.fornavn, ''))
        || TO_TSVECTOR('simple', COALESCE(p.etternavn, ''))
        || TO_TSVECTOR('simple', COALESCE(STRING_AGG(pivot.pseudonym, ' '), ''))
             AS any_field_ts

FROM litteraturkritikk_personer AS p

-- person
         LEFT JOIN litteraturkritikk_record_person AS pivot
                   ON p.id = pivot.person_id

         LEFT JOIN jsonb_array_elements_text(pivot.person_role) as role_n on true

GROUP BY p.id;
