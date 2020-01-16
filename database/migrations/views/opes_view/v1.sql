CREATE MATERIALIZED VIEW opes_view AS
SELECT
    opes.*
    , pub.ser_vol
    , pub.editor
    , pub.year
    , pub.pg_no
    , pub.photo
    , pub.sb
    , pub.corrections
    , pub.preferred_citation
    , pub.ddbdp_pmichcitation
FROM opes
JOIN opes_publications AS pub
    ON pub.opes_id = opes.id;

CREATE UNIQUE INDEX opes_view_id ON opes_view (id);
