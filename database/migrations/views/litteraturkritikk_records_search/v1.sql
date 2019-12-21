----------------------------------------------------------------------------------------
-- Create litteraturkritikk_records_search

CREATE MATERIALIZED VIEW litteraturkritikk_records_search AS
SELECT

    r.*,

    SUBSTR(TRIM(dato),1,4) AS dato_numeric,

    -- Flat representasjon for tabellvisning
    STRING_AGG(DISTINCT forfatter.etternavn_fornavn, '; ') AS verk_forfatter,
    STRING_AGG(DISTINCT kritiker.etternavn_fornavn, '; ') AS kritiker,

    -- Søkeindeks 'any_field_ts'
    TO_TSVECTOR('simple', COALESCE(r.tittel, ''))
    || TO_TSVECTOR('simple', COALESCE(r.publikasjon, ''))
    || TO_TSVECTOR('simple', COALESCE(r.dato, ''))
    || TO_TSVECTOR('simple', COALESCE(r.bind, ''))
    || TO_TSVECTOR('simple', COALESCE(r.hefte, ''))
    || TO_TSVECTOR('simple', COALESCE(r.sidetall, ''))
    || TO_TSVECTOR('simple', COALESCE(r.kommentar, ''))
    || TO_TSVECTOR('simple', COALESCE(r.utgivelseskommentar, ''))
    || TO_TSVECTOR('simple', COALESCE(r.verk_tittel, ''))
    || TO_TSVECTOR('simple', COALESCE(r.verk_dato, ''))
    || TO_TSVECTOR('simple', COALESCE(r.verk_kommentar, ''))
    || TO_TSVECTOR('simple', COALESCE(STRING_AGG(person_pivot.kommentar, ' '), ''))
    || TO_TSVECTOR('simple', COALESCE(STRING_AGG(person_pivot.pseudonym, ' '), ''))
    || TO_TSVECTOR('simple', COALESCE(STRING_AGG(person.etternavn, ' '), ''))
    || TO_TSVECTOR('simple', COALESCE(STRING_AGG(person.fornavn, ' '), ''))
    AS any_field_ts,

    -- Søkeindeks 'verk_tittel_ts'
    TO_TSVECTOR('simple', COALESCE(r.verk_tittel, ''))
    AS verk_tittel_ts,

    -- Søkeindeks 'forfatter_ts'.
    TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT forfatter.etternavn_fornavn, ' '), ''))
    || TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT forfatter.fornavn_etternavn, ' '), ''))
    || TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT forfatter_pivot.pseudonym, ' '), ''))
    AS forfatter_ts,

    -- Søkeindeks 'kritiker_ts'
    TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT kritiker.etternavn_fornavn, ' '), ''))
    || TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT kritiker.fornavn_etternavn, ' '), ''))
    || TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT kritiker_pivot.pseudonym, ' '), ''))
    AS kritiker_ts,

    -- Søkeindeks 'person_ts'
    TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT person.etternavn_fornavn, ' '), ''))
    || TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT person.fornavn_etternavn, ' '), ''))
    || TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT person_pivot.pseudonym, ' '), ''))
    AS person_ts

FROM litteraturkritikk_records AS r

-- person
LEFT JOIN litteraturkritikk_record_person AS person_pivot
    ON r.id = person_pivot.record_id

    LEFT JOIN litteraturkritikk_personer_view AS person
        ON person.id = person_pivot.person_id

-- kritiker
LEFT JOIN litteraturkritikk_record_person AS kritiker_pivot
    ON r.id = kritiker_pivot.record_id
    AND kritiker_pivot.person_role = 'kritiker'

    LEFT JOIN litteraturkritikk_personer_view AS kritiker
        ON kritiker.id = kritiker_pivot.person_id

-- forfatter
LEFT JOIN litteraturkritikk_record_person AS forfatter_pivot
    ON forfatter_pivot.record_id = r.id
    AND forfatter_pivot.person_role != 'kritiker'

    LEFT JOIN litteraturkritikk_personer_view AS forfatter
        ON forfatter.id = forfatter_pivot.person_id

GROUP BY r.id;

----------------------------------------------------------------------------------------
-- Standard indices

CREATE UNIQUE INDEX litteraturkritikk_records_search_id_idx
ON litteraturkritikk_records_search (id);

CREATE INDEX litteraturkritikk_records_search_publikasjon_idx
ON litteraturkritikk_records_search (publikasjon);

CREATE INDEX litteraturkritikk_records_search_verk_sjanger_idx
ON litteraturkritikk_records_search (verk_sjanger);

CREATE INDEX litteraturkritikk_records_search_verk_kritikktype_idx
ON litteraturkritikk_records_search (kritikktype);

CREATE INDEX litteraturkritikk_records_search_verk_spraak_idx
ON litteraturkritikk_records_search (spraak);

----------------------------------------------------------------------------------------
-- GIN indices

CREATE INDEX litteraturkritikk_any_field_ts_idx
ON litteraturkritikk_records_search USING gin(any_field_ts);

CREATE INDEX litteraturkritikk_forfatter_ts_idx
ON litteraturkritikk_records_search USING gin(forfatter_ts);

CREATE INDEX litteraturkritikk_kritiker_ts_idx
ON litteraturkritikk_records_search USING gin(kritiker_ts);

CREATE INDEX litteraturkritikk_person_ts_idx
ON litteraturkritikk_records_search USING gin(person_ts);
