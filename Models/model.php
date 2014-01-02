<?php
/**
 * Tis file is part of Nacatiks
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://www.nacatikx.dafun.com/forum/index.php
 *
 * Copyright (c) 2012-Present, Nacatiks Support Team <http://www.nacatikx.dafun.com/index.php?page=Accueil>
 * All rights reserved.
 *=========================================================
  _   _          _____       _______ _____ _  __ _____ 
 | \ | |   /\   / ____|   /\|__   __|_   _| |/ // ____|
 |  \| |  /  \ | |       /  \  | |    | | | ' /| (___  
 | . ` | / /\ \| |      / /\ \ | |    | | |  <  \___ \ 
 | |\  |/ ____ \ |____ / ____ \| |   _| |_| . \ ____) |
 |_| \_/_/    \_\_____/_/    \_\_|  |_____|_|\_\_____/                                                                          
 *=========================================================
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
		var_dump($db);
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