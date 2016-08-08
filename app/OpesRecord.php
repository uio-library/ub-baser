<?php
namespace App;





// denne ikluderer alle feltene i opes tabellen

class OpesRecord extends Record
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'opes';


// denne henter alle publikasjoner for denne papyrus
// 2 august 2016
    public function GetPubl()
    {
        return $this->hasMany('App\PubOpes');
    }

     
/*

    public function  section_or_side()
    {
      $repr = $this->section_or_side;
     return $repr;
    } 
    
    public function material() 
    {
      $repr = $this->material;
     return $repr;
    } 

    public function connections() 
    {
      $repr = $this->connections;
     return $repr;
    } 
    
    public function rep_ser_old()
    {
      $repr = $this->rep_ser_old;
     return $repr;
    } 

    public function rep_pg_no_old()
    {
      $repr = $this->rep_ser_old;
     return $repr;
    } 

   public function mounted()
    {
      $repr = $this->mounted;
     return $repr;
    } 

    public function negative()
    {
      $repr = $this->negative;
     return $repr;
    } 

    public function acquisition()
    {
      $repr = $this->acquisition;
     return $repr;
    } 

    public function type_of_text_file()
    {
      $repr = $this->type_of_text_file;
     return $repr;
    } 

    public function size()
    {
      $repr = $this->size;
     return $repr;
    } 

    public function notes_preserv()
    {
      $repr = $this->notes_preserv;
     return $repr;
    } 

    public function notes()
    {
      $repr = $this->notes;
     return $repr;
    }   

    public function date_scanned()
    {
      $repr = $this->date_scanned;
     return $repr;
    } 


    public function time_to_scan()
    {
      $repr = $this->time_to_scan;
     return $repr;
    } 

    public function scanner_initials()
    {
      $repr = $this->scanner_initials;
     return $repr;
    } 

    public function full_size_front()
    {
      $repr = $this->full_size_front;
     return $repr;
    } 

    public function full_size_back()
    {
      $repr = $this->full_size_back;
     return $repr;
    } 

    public function provenance()
    {
      $repr = $this->provenance;
     return $repr;
    } 

    public function language()
    {
      $repr = $this->language;
     return $repr;
    } 

    public function lines()
    {
      $repr = $this->lines;
     return $repr;
    } 

    public function palaeogr_descr()
    {
      $repr = $this->palaeogr_descr;
     return $repr;
    } 

    public function further_rep()
    {
      $repr = $this->further_rep;
     return $repr;
    } 

    public function author()
    {
      $repr = $this->author;
     return $repr;
    } 

    public function date_preapis3()
    {
      $repr = $this->date_preapis3;
     return $repr;
    } 

    public function library()
    {
      $repr = $this->library;
     return $repr;
    } 

    public function content()
    {
      $repr = $this->content;
     return $repr;
    } 

    public function persons()
    {
      $repr = $this->persons;
     return $repr;
    } 

    public function geographica()
    {
      $repr = $this->geographica;
     return $repr;
    } 

    public function cataloger_initials()
    {
      $repr = $this->cataloger_initials;
     return $repr;
    } 

    public function further_rep_corr()
    {
      $repr = $this->further_rep_corr;
     return $repr;
    } 

    public function avail_system_req()
    {
      $repr = $this->avail_system_req;
     return $repr;
    } 

    public function extent()
    {
      $repr = $this->extent;
     return $repr;
    } 

    public function image_arrang()
    {
      $repr = $this->image_arrang;
     return $repr;
    } 

    public function image_creation()
    {
      $repr = $this->image_creation;
     return $repr;
    } 

    public function institution()
    {
      $repr = $this->institution;
     return $repr;
    } 

    public function assignment()
    {
      $repr = $this->assignment;
     return $repr;
    } 

    public function assign_date()
    {
      $repr = $this->assign_date;
     return $repr;
    } 

    public function research_sta()
    {
      $repr = $this->research_sta;
     return $repr;
    } 

    public function el_editor()
    {
      $repr = $this->el_editor;
     return $repr;
    } 

    public function el_publ_date()
    {
      $repr = $this->el_publ_date;
     return $repr;
    } 

    public function revision_hist()
    {
      $repr = $this->revision_hist;
     return $repr;
    } 

    public function origin()
    {
      $repr = $this->origin;
     return $repr;
    } 

    public function conserv_status()
    {
      $repr = $this->conserv_status;
     return $repr;
    } 

    public function items()
    {
      $repr = $this->items;
     return $repr;
    } 

    public function publ_side()
    {
      $repr = $this->publ_side;
     return $repr;
    } 

    public function genre()
    {
      $repr = $this->genre;
     return $repr;
    } 

    public function translation()
    {
      $repr = $this->translation;
     return $repr;
    } 

    public function check()
    {
      $repr = $this->check;
     return $repr;
    } 

    public function status()
    {
      $repr = $this->status;
     return $repr;
    } 

    public function bibliography()
    {
      $repr = $this->bibliography;
     return $repr;
    } 

    public function negative_in_copenhagen()
    {
      $repr = $this->negative_in_copenhagen;
     return $repr;
    } 

    public function image_source()
    {
      $repr = $this->image_source;
     return $repr;
    } 

    public function scanning_medium()
    {
      $repr = $this->scanning_medium;
     return $repr;
    } 

    public function date_cataloged()
    {
      $repr = $this->date_cataloged;
     return $repr;
    } 

    public function further_rep_sb()
    {
      $repr = $this->further_rep_sb;
     return $repr;
    } 

    public function quote()
    {
      $repr = $this->quote;
     return $repr;
    } 

    public function further_replication_note()
    {
      $repr = $this->further_replication_note;
     return $repr;
    } 

    public function extent_genre()
    {
      $repr = $this->extent_genre;
     return $repr;
    } 

    public function language_code()
    {
      $repr = $this->language_code;
     return $repr;
    } 

    public function date1()
    {
      $repr = $this->date1;
     return $repr;
    } 

    public function date2()
    {
      $repr = $this->date2;
     return $repr;
    } 

    public function date()
    {
      $repr = $this->date;
     return $repr;
    } 

    public function date_created()
    {
      $repr = $this->date_created;
     return $repr;
    } 

    public function date_modified()
    {
      $repr = $this->date_modified;
     return $repr;
    } 

    public function ddbdp_p_fur()
    {
      $repr = $this->ddbdp_p_fur;
     return $repr;
    } 

    public function ddbdp_o_fur()
    {
      $repr = $this->ddbdp_o_fur;
     return $repr;
    } 

    public function title_statement()
    {
      $repr = $this->title_statement;
     return $repr;
    } 

    public function material_long()
    {
      $repr = $this->material_long;
     return $repr;
    } 

    public function subj_headings()
    {
      $repr = $this->subj_headings;
     return $repr;
    } 

    public function multispectral()
    {
      $repr = $this->multispectral;
     return $repr;
    } 
// problem with this as it had a - before the wl
    public function multispectral_wl()
    {
      $repr = $this->multispectral_wl;
     return $repr;
    } 

    public function location()
    {
      $repr = $this->location;
     return $repr;
    } 

    public function id2()
    {
      $repr = $this->id2;
     return $repr;
    } */

     
}