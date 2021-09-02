CREATE MATERIALIZED VIEW opes_view AS
SELECT
    *

    -- inv_no_sort
    , 10 * cast(coalesce(nullif(REGEXP_REPLACE(inv_no, '[^0-9]', '', 'g'), ''), '0') as int)
      + CASE
          WHEN inv_no LIKE '%r' THEN 1
          WHEN inv_no LIKE '%v' THEN 2
          ELSE 0
        END
    AS inv_no_sort

    -- standard_designation_sort
    , 1e5 * CASE WHEN standard_designation NOT LIKE 'P.Oslo inv%' THEN 1 ELSE 2 END
      + 10 * cast(coalesce(nullif(REGEXP_REPLACE(standard_designation, '[^0-9]', '', 'g'), ''), '0') as int)
      + CASE
          WHEN standard_designation LIKE '%r' THEN 1
          WHEN standard_designation LIKE '%v' THEN 2
          ELSE 0
        END
    AS standard_designation_sort

FROM (
    SELECT opes.*

          -- Publications
          -- Commenting these out for now, should probably be coalesce some of them?
          -- , pub.edition_nr
         -- , pub.ser_vol
         -- , pub.editor
         -- , pub.year
         -- , pub.pg_no
         -- , pub.photo
         -- , pub.corrections
         -- , pub.preferred_citation

          -- Standard designation
          -- TODO: Holder det å ha den her, eller trenger vi den på Record?
          , CASE
              WHEN opes.p_oslo_vol IS NOT NULL
              THEN 'P.Oslo ' || opes.p_oslo_vol || ' ' || opes.p_oslo_nr
              ELSE 'P.Oslo inv. ' || opes.inv_no
            END
          AS standard_designation

          -- Søkeindeks 'any_field_ts'
          , TO_TSVECTOR('simple', COALESCE(opes.*::text, ''))
          -- || TO_TSVECTOR('simple', COALESCE(pub.*::text, ''))
          AS any_field_ts

    FROM opes
    LEFT JOIN opes_editions AS pub
        ON pub.opes_id = opes.id

    GROUP BY opes.id

) temporary_table;

CREATE UNIQUE INDEX opes_view_id ON opes_view (id);
