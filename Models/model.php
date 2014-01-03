<?php
/**
 * This file is part of phpSpaceProject
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://phpsp.fr/
 *
 * Copyright (c) 2012-Present, phpSpaceProject Support Team <http://phpsp.fr/board/>
 * All rights reserved.
 *===================================
  _____  _    _ _____   _____ _____  
 |  __ \| |  | |  __ \ / ____|  __ \ 
 | |__) | |__| | |__) | (___ | |__) |
 |  ___/|  __  |  ___/ \___ \|  ___/ 
 | |    | |  | | |     ____) | |     
 |_|    |_|  |_|_|    |_____/|_|                 
 *===================================
 *
 */
class Model
{
	public $table;
	public $id;
	
    public function __construct()
    {
        global $db;
        $this->db = $db;
    }
	
	/* fonction qui permet de lire */
	public function read($fields=null)
	{
		if($fields==null)
		{
			$fields = "*";
		}

			$sql = "SELECT ".$fields." FROM ".$this->table." WHERE id=".$this->id;
			$sth = $this->db->prepare($sql);
			$sth->execute();
			$data = $sth->fetch();
			foreach ($data as $key => $value)
			{
				$this->$key=$value;
			}
	}


	/* fonction qui permet d'enregitrer */
	public function save($data)
	{
		if(isset($data["id"]) && !empty($data["id"]))
		{
			$sql ="UPDATE ".$this->table." SET ";
			foreach ($data as $key => $value) {
				if($key !="id")
				{
					$sql .= "$key='$value',";
				}
			}
			$sql = substr($sql,0,-1);
			$sql .= " WHERE id=".$data["id"];
		}
		else
		{ 
			$sql = "INSERT INTO ".$this->table."(";
			unset($data["id"]);
			foreach ($data as $key => $value) {
					$sql .="$key,";
			}
			$sql = substr($sql,0,-1);
			$sql .=") VALUES (";
			foreach ($data as $value) {
					$sql .="'$value',";
			}
			$sql = substr($sql,0,-1);
			$sql .= ");";

		}
		$sth = $this->db->prepare($sql);
		$sth->execute();
		$insert = $sth->rowCount();
		if(!isset($data["id"]))
		{
			$this->id = $this->db->lastInsertId(); 
		}
		else
		{
			$this->id = $data["id"];
		}
	}

	// fonction qui permet de selectionner avec certains parametre 
	public function find($data=array())
	{
		$conditions ="";// présciser le WHERE
		$fields ="*";
		$limit ="";
		$order ="";//préscise le ORDER BY

		if(isset($data["conditions"])){ $conditions = $data["conditions"];}
		if(isset($data["fields"])){ $fields = $data["fields"];}
		if(isset($data["limit"])){ $limit = "LIMIT ".$data["limit"];}
		if(isset($data["order"])){ $order = $data["order"];}

		$sql = "SELECT $fields FROM ".$this->table." $conditions $order $limit";
		$sth = $this->db->prepare($sql);
		$sth->execute();
		$result = $sth->fetchAll();

		return $result;
	}

	// fonction qui permet de supprimer
	public function delete($id=null)
	{
		if($id==null)
		{
			$id = $this->id;
		}
		
		$sql = "DELETE FROM ".$this->table." WHERE id=$id";
		$sth = $this->db->prepare($sql);
		$sth->execute();
		$delete = $sth->rowCount();
	}

	// fonction qui permet d'aller chercher le fichier model
	static function load($name)
	{
		require("$name.php");
		return new $name();
	}
}