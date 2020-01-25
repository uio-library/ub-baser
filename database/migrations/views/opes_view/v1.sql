CREATE MATERIALIZED VIEW opes_view AS
SELECT
    opes.*

    -- Publications
    , pub.ser_vol
    , pub.editor
    , pub.year
    , pub.pg_no
    , pub.photo
    , pub.sb
    , pub.corrections
    , pub.preferred_citation
    , pub.ddbdp_pmichcitation

    -- Søkeindeks 'any_field_ts'
    , TO_TSVECTOR('simple', COALESCE(opes.*::text, ''))
      || TO_TSVECTOR('simple', COALESCE(pub.*::text, ''))
      AS any_field_ts

FROM opes
JOIN opes_publications AS pub
    ON pub.opes_id = opes.id;

CREATE UNIQUE INDEX opes_view_id ON opes_view (id);
