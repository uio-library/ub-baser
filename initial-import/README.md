
### Notes on the Litteraturkritikk dataset

`litteraturkritikk.json` was prepared from a TSV export from Filemaker
and imported on 16 August 2019. The intention was to preserve the original IDs from the Filemaker records.

On 1 September 2019 it was found that the IDs for records starting from 22551 did *not* match the original records. It seems like the ID column in the first export may have been just an incremental sequence rather than the true IDs as it contains no gaps. The original ID sequence also contains no gaps until record 22550, but starting from 22551 there are some gaps, probably due to deleted records. A new export was therefore carried out, stored as `litteraturkritikk.original_ids.json`.

Since the database had already received dozens of edits at this time, and since the IDs did match for most of the dataset, a re-import was not carried out. However, if there is a need to lookup one of the original IDs beyond 22550, it can be done by checking the `litteraturkritikk.original_ids.json` file. Locate the line number of the line containing the original ID (first column) and subtract 1 to find the new ID. For instance, the original ID `23647` is located on line `23551` in `litteraturkritikk.original_ids.json`. Its new ID is therefore `23551 - 1 = 23550`.
