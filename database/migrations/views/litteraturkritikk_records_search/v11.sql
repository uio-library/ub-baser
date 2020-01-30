-- --------------------------------------------------------------------------------------
-- Create litteraturkritikk_records_search

-- DROP MATERIALIZED VIEW litteraturkritikk_records_search
CREATE MATERIALIZED VIEW litteraturkritikk_records_search AS
SELECT

    r.id,
    r.created_at,
    r.updated_at,
    r.deleted_at,
    r.kritikktype,
    r.spraak,
    r.tittel,
    r.publikasjon,
    r.utgivelsessted,
    r.dato,
    r.aargang,
    r.nummer,
    r.bind,
    r.hefte,
    r.sidetall,
    r.utgivelseskommentar,
    r.kommentar,
    r.kritiker_mfl,
    r.fulltekst_url,
    r.korrekturstatus,
    r.tags,
    r.medieformat,
    r.discusses_more,

    SUBSTR(TRIM(dato),1,4) AS dato_numeric,

    -- Flat representasjon for tabellvisning
    STRING_AGG(DISTINCT critic.etternavn_fornavn, '; ') AS kritiker,

    TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT critic.etternavn_fornavn, ' '), ''))
    -- Begge veier for å gi treff ved prasesøk på både "Etternavn, Fornavn" og "Fornavn Etternavn"
    -- Litt mer komplisert for verk, kanskje vi må legge til fornavn_etternavn på works også
    -- || TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT critic.fornavn_etternavn, ' '), ''))
    || TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT critic_pivot.pseudonym, ' '), ''))
    AS kritiker_ts,

    STRING_AGG(DISTINCT critic.kjonn, '; ') AS kritiker_kjonn,

    -- Forfatter
    array_to_string(
        array_cat(
            array_remove(array_agg(subject_work.verk_forfatter), NULL),
            array_remove(array_agg(subject_person.etternavn_fornavn), NULL)
        ),
        '; '
    ) AS forfatter,

    TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT subject_work.verk_forfatter, ' '), ''))
    || TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT subject_work.verk_forfatter_pseudonym, ' '), ''))
    || TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT subject_person.etternavn_fornavn, ' '), ''))
    AS forfatter_ts,


    STRING_AGG(DISTINCT subject_work.verk_tittel, chr(10)) AS verk_tittel,
    STRING_AGG(DISTINCT subject_work.verk_forfatter_kjonn, chr(10)) AS verk_forfatter_kjonn,

    STRING_AGG(DISTINCT subject_work.verk_dato, chr(10)) AS verk_dato,
    STRING_AGG(DISTINCT subject_work.verk_originaltittel, chr(10)) AS verk_originaltittel,
    STRING_AGG(DISTINCT subject_work.verk_originaltittel_transkribert, chr(10)) AS verk_originaltittel_transkribert,
    STRING_AGG(DISTINCT subject_work.verk_originaldato, chr(10)) AS verk_originaldato,
    STRING_AGG(DISTINCT subject_work.verk_sjanger, chr(10)) AS verk_sjanger,
    STRING_AGG(DISTINCT subject_work.verk_spraak, chr(10)) AS verk_spraak,
    STRING_AGG(DISTINCT subject_work.verk_originalspraak, chr(10)) AS verk_originalspraak,
    STRING_AGG(DISTINCT subject_work.verk_kommentar, chr(10)) AS verk_kommentar,
    STRING_AGG(DISTINCT subject_work.verk_utgivelsessted, chr(10)) AS verk_utgivelsessted,

    -- Søkeindeks 'any_field_ts'
    TO_TSVECTOR('simple', r.id::text)
    || TO_TSVECTOR('simple', COALESCE(r.tittel, ''))
    || TO_TSVECTOR('simple', COALESCE(r.publikasjon, ''))
    || TO_TSVECTOR('simple', COALESCE(r.dato, ''))
    || TO_TSVECTOR('simple', COALESCE(r.bind, ''))
    || TO_TSVECTOR('simple', COALESCE(r.hefte, ''))
    || TO_TSVECTOR('simple', COALESCE(r.sidetall, ''))
    || TO_TSVECTOR('simple', COALESCE(r.kommentar, ''))
    || TO_TSVECTOR('simple', COALESCE(r.utgivelseskommentar, ''))
    || TO_TSVECTOR('simple', r.kritikktype::text)
    || TO_TSVECTOR('simple', r.tags::text)
    || tsvector_agg(subject_work.any_field_ts)
    -- TO_TSVECTOR('simple', COALESCE(STRING_AGG(subject_work.verk_tittel, ' '), ''))
    -- || TO_TSVECTOR('simple', COALESCE(STRING_AGG(subject_work.verk_originaltittel, ' '), ''))
    -- || TO_TSVECTOR('simple', COALESCE(STRING_AGG(subject_work.verk_originaltittel_transkribert, ' '), ''))
    -- || TO_TSVECTOR('simple', COALESCE(STRING_AGG(subject_work.verk_dato, ' '), ''))
    -- || TO_TSVECTOR('simple', COALESCE(STRING_AGG(subject_work.verk_originaldato, ' '), ''))
    -- || TO_TSVECTOR('simple', COALESCE(STRING_AGG(subject_work.verk_kommentar, ' '), ''))
    -- || TO_TSVECTOR('simple', COALESCE(STRING_AGG(subject_work.verk_sjanger, ' '), ''))
    || TO_TSVECTOR('simple', COALESCE(STRING_AGG(critic_pivot.kommentar, ' '), ''))
    || TO_TSVECTOR('simple', COALESCE(STRING_AGG(critic_pivot.pseudonym, ' '), ''))
    || TO_TSVECTOR('simple', COALESCE(STRING_AGG(critic.etternavn, ' '), ''))
    || TO_TSVECTOR('simple', COALESCE(STRING_AGG(critic.fornavn, ' '), ''))
    AS any_field_ts,

    -- Søkeindekser tittel
    tsvector_agg(subject_work.verk_tittel_ts) AS verk_tittel_ts,
    tsvector_agg(subject_work.verk_originaltittel_ts) AS verk_originaltittel_ts,
    tsvector_agg(subject_work.verk_originaltittel_transkribert_ts) AS verk_originaltittel_transkribert_ts_ts,



    -- Søkeindeks 'omtalt_person_ts'
    tsvector_agg(subject_person.any_field_ts) AS omtalt_person_ts,

    -- Søkeindeks 'person_ts'
    TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT subject_work.verk_forfatter, ' '), ''))
    || TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT subject_person.etternavn_fornavn, ' '), ''))
    || TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT subject_work.verk_forfatter_pseudonym, ' '), ''))
    || TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT critic.etternavn_fornavn, ' '), ''))
    || TO_TSVECTOR('simple', COALESCE(STRING_AGG(DISTINCT critic_pivot.pseudonym, ' '), ''))
    AS person_ts,

    MIN(subject_work.verk_dato_s) AS verk_dato_s,
    MIN(subject_work.verk_originaldato_s) AS verk_originaldato_s


FROM litteraturkritikk_records AS r

-- kritiker / critic
LEFT JOIN litteraturkritikk_person_contributions AS critic_pivot
    ON r.id = critic_pivot.contribution_id AND critic_pivot.contribution_type = 'App\Bases\Litteraturkritikk\Record'

    LEFT JOIN litteraturkritikk_personer_view AS critic
        ON critic.id = critic_pivot.person_id

-- omtalt person / person as subject
LEFT JOIN litteraturkritikk_subject_person AS subject_person_pivot
    ON r.id = subject_person_pivot.record_id

    LEFT JOIN litteraturkritikk_personer_view AS subject_person
    ON subject_person.id = subject_person_pivot.person_id

-- omtalt verk / work as subject
LEFT JOIN litteraturkritikk_subject_work AS subject_work_pivot
    ON r.id = subject_work_pivot.record_id

    LEFT JOIN litteraturkritikk_works_view AS subject_work
    ON subject_work.id = subject_work_pivot.work_id

GROUP BY r.id;

-- --------------------------------------------------------------------------------------
-- Standard indices

CREATE UNIQUE INDEX litteraturkritikk_records_search_id_idx
ON litteraturkritikk_records_search (id);

CREATE INDEX litteraturkritikk_records_search_publikasjon_idx
ON litteraturkritikk_records_search (publikasjon);

-- CREATE INDEX litteraturkritikk_records_search_verk_sjanger_idx
-- ON litteraturkritikk_records_search (verk_sjanger);

-- CREATE INDEX litteraturkritikk_records_search_verk_kritikktype_idx
-- ON litteraturkritikk_records_search (kritikktype);

-- --------------------------------------------------------------------------------------
-- GIN indices

CREATE INDEX litteraturkritikk_any_field_ts_idx
ON litteraturkritikk_records_search USING gin(any_field_ts);

CREATE INDEX litteraturkritikk_forfatter_ts_idx
ON litteraturkritikk_records_search USING gin(forfatter_ts);

CREATE INDEX litteraturkritikk_kritiker_ts_idx
ON litteraturkritikk_records_search USING gin(kritiker_ts);

CREATE INDEX litteraturkritikk_person_ts_idx
ON litteraturkritikk_records_search USING gin(person_ts);
