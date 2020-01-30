-- DROP VIEW litteraturkritikk_works_view;
CREATE VIEW litteraturkritikk_works_view AS
SELECT
    work.*,

    STRING_AGG(person_pivot.pseudonym, '; ') AS verk_forfatter_pseudonym,

    -- Flat representasjon for tabellvisning
    STRING_AGG(person.etternavn_fornavn, '; ') AS verk_forfatter,
    STRING_AGG(person.kjonn, '; ') AS verk_forfatter_kjonn,

    -- Søkeindeks 'any_field_ts'.
    TO_TSVECTOR('simple', COALESCE(work.verk_tittel, ''))
    || TO_TSVECTOR('simple', COALESCE(work.verk_originaltittel, ''))
    || TO_TSVECTOR('simple', COALESCE(work.verk_originaltittel_transkribert, ''))
    || TO_TSVECTOR('simple', COALESCE(work.verk_dato, ''))
    || TO_TSVECTOR('simple', COALESCE(work.verk_originaldato, ''))
    || TO_TSVECTOR('simple', COALESCE(work.verk_kommentar, ''))
    || TO_TSVECTOR('simple', COALESCE(work.verk_sjanger, ''))

    || TO_TSVECTOR('simple', COALESCE(STRING_AGG(person.etternavn, ' '), ''))
    || TO_TSVECTOR('simple', COALESCE(STRING_AGG(person.fornavn, ' '), ''))
    AS any_field_ts,

    -- Søkeindeks 'verk_tittel_ts'
    TO_TSVECTOR('simple', COALESCE(work.verk_tittel, ''))
    || TO_TSVECTOR('simple', COALESCE(work.verk_originaltittel, ''))
    || TO_TSVECTOR('simple', COALESCE(work.verk_originaltittel_transkribert, ''))
    AS verk_tittel_ts,

    -- Søkeindeks 'verk_originaltittel_ts'
    TO_TSVECTOR('simple', COALESCE(work.verk_originaltittel, ''))
    AS verk_originaltittel_ts,

    -- Søkeindeks 'verk_originaltittel_transkribert_ts'
    TO_TSVECTOR('simple', COALESCE(work.verk_originaltittel_transkribert, ''))
    AS verk_originaltittel_transkribert_ts,

    -- Sorteringsindeks 'verk_dato_s'
    (
        CASE
            WHEN verk_dato IS NULL THEN
                9999
            WHEN REGEXP_REPLACE(verk_dato, '[^0-9].*', '') = '' THEN
                9999
            WHEN verk_dato LIKE '% fvt.' THEN
                REGEXP_REPLACE(verk_dato, '[^0-9].*', '')::int * -1
            ELSE
                REGEXP_REPLACE(verk_dato, '[^0-9].*', '')::int
        END
    ) AS verk_dato_s,

    -- Sorteringsindeks 'verk_originaldato_s'
    (
        CASE
            WHEN verk_originaldato IS NULL THEN
                9999
            WHEN REGEXP_REPLACE(verk_originaldato, '[^0-9].*', '') = '' THEN
                9999
            WHEN verk_dato LIKE '% fvt.' THEN
                REGEXP_REPLACE(verk_originaldato, '[^0-9].*', '')::int * -1
            ELSE
                REGEXP_REPLACE(verk_originaldato, '[^0-9].*', '')::int
        END
    ) AS verk_originaldato_s


FROM litteraturkritikk_works AS work

-- person
LEFT JOIN litteraturkritikk_person_contributions AS person_pivot
    ON person_pivot.contribution_type = 'App\Bases\Litteraturkritikk\Work'
    AND person_pivot.contribution_id = work.id

    LEFT JOIN litteraturkritikk_personer_view AS person
        ON person.id = person_pivot.person_id

GROUP BY work.id;
