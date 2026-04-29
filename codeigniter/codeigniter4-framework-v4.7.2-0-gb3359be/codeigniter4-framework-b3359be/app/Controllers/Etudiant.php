<?php
namespace App\Controllers;
class Etudiant extends BaseController
{
public function index()
{
return "Liste des étudiants";
}
public function showAll()
{
return view('etudiant_list');
}
}