<?php

	$_SESSION['current_date'] = "";
	require('pdo.php');

	//Function for getting the awailable capacity of the inventory
	function getAwailibility($id)	{
	
		$sum = getFilled($id);
		$stmt = $pdo->prepare('Select capacity from inventory where inventory_id = :id');
		$stmt->execute(array(":id" => $id));
		$cap = $stmt->fetch(PDO::FETCH_ASSOC);
		return $cap['capacity'] - $sum;
	}

	function getFilled($id)	{

		$stmt = $pdo->prepare("SELECT quantity FROM productinventory where inventory_id = :id");
		$stmt->execute(array(":id" => $id));
		$quant = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$sum=0;
		foreach ($quant as $q)	{
			$sum += $q['quantity'];
		}
		return $sum;
	}

	function getMinimumProductAmount($pro_id)	{

		$con = mysqli_connect("localhost","root","TrippleAa","inventorydata",3306);
		if (mysqli_connect_errno())		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		$res = mysqli_query($con,"SELECT minInA, minInB, minInC FROM product where product_id=".$pro_id);
		$q = $res->fetch_assoc();

		$arr[0] = $q['minInA'];
		$arr[1] = $q['minInB'];
		$arr[2] = $q['minInC'];

		mysqli_close($con);
		return $arr;
	}

	function getProductSoldInInventory($pro_id, $inv_id)	{

		$stmt = $pdo->prepare("SELECT quantity FROM purchase WHERE inventory_id = ".$inv_id." AND product_id = ".$pro_id);
		$stmt->execute();
		$quant = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$sum=0;
		foreach ($quant as $q)	{
			$sum += $q['quantity'];
		}
		return $sum;
	}

	function getProductInInventory($pro_id, $inv_id)	{

		$stmt = $pdo->prepare("SELECT quantity FROM productinventory WHERE inventory_id = ".$inv_id." AND product_id = ".$pro_id);
		$stmt->execute();
		$quant = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$sum=0;
		foreach ($quant as $q)	{
			$sum += $q['quantity'];
		}
		return $sum;
	}

	function checkProductStatus($pro_id)	{

		$availInA = getAwailibility(1);
		$availInB = getAwailibility(2);
		$availInC = getAwailibility(3);
		$availInv = array("availInA"=>$availInA, "availInB"=>$availInB, "availInC"=>$availInC);

		$qInA = getProductInInventory($pro_id, 1);
		$qInB = getProductInInventory($pro_id, 2);
		$qInC = getProductInInventory($pro_id, 3);

		$qInInv = array("qInA"=>$qInA, "qInB"=>$qInB, "qInC"=>$qInC);

		$sInA = getProductSoldInInventory($pro_id, 1);
		$sInB = getProductSoldInInventory($pro_id, 2);
		$sInC = getProductSoldInInventory($pro_id, 3);

		$sInInv = array("sInA"=>$sInA, "sInB"=>$sInB, "sInC"=>$sInC);

		$m = getMinimumProductAmount($pro_id);
		$mA = $m[0];
		$mB = $m[1];
		$mC = $m[2];
		$mInv = array("mA"=>$mA, "mB"=>$mB, "mC"=>$mC);

		$dA = $qInA - $mA;
		$dB = $qInB - $mB;
		$dC = $qInC - $mC;

		$AtoB = 0;
		$AtoC = 0;
		$BtoA = 0;
		$BtoC = 0;
		$CtoA = 0;
		$CtoB = 0;

		if($qInA < $mA)	{
			if($dB > $dC)	{
				if($qInB > $qinA && $dB>0)	{
					$BtoA = $dB;
				}
			}
			else	{
				if($qInC > $qinA && $dC>0)	{
					$CtoA = $dC;
				}
			}
		}

		if($qInB < $mB)	{
			if($dA > $dC)	{
				if($qInA > $qInB && $dA>0)	{
					$AtoB = $dA;
				}
			}
			else	{
				if($qInC > $qinB && $dC>0)	{
					$CtoB = $dC;
				}
			}
		}

		if($qInC < $mC)	{
			if($dB > $dA)	{
				if($qInB > $qinC && $dB>0)	{
					$BtoC = $dB;
				}
			}
			else	{
				if($qInA > $qinC && $dA>0)	{
					$AtoC = $dC;
				}
			}
		}
		$qS = array("AtoB"=>$AtoB, "AtoC"=>$AtoC, "BtoA"=>$BtoA, "BtoC"=>$BtoC, "CtoA"=>$CtoA, "CtoB"=>$CtoB);
		$data = array_merge($availInv, $qInInv, $sInInv, $mInv, $qS);
		$json = json_encode($data);
		return $json;
	}


	function agingDiscount($pro_id)	{
		$con = mysqli_connect("localhost","root","","inventorydata",3306);
		if (mysqli_connect_errno())		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

		$invA = 0;
		$invB = 0;
		$invC = 0;

		$query = "SELECT date_added, date_aging, quantity, inventory_id from productinventory where product_id = ".$pro_id;
		$res = mysqli_query($con, $query);
		$q = $res->fetch_assoc();

		foreach($q as $var)	{
			$days_passed = daysDifference($var['date_added'], $_SESSION['current_date']);
			$discount = 0;
			$days_remaining = daysDifference($_SESSION['current_date'], $var['date_aging']);
			if($days_passed < 90 && $days_remaining<5)	{
				$discount = 15;
			}
			else if($days_passed < 90 && $days_remaining<10)	{
				$discount = 10;
			}
			else if($days_passed < 90 && $days_remaining<15)	{
				$discount = 5;
			}
			else if($days_passed > 90)	{
				$discount = 25;
			}
			$query = "Update productinventory SET discount = ".$discount."where product_id = ".$pro_id."and $inventory_id=".$var['inventory_id']."and date_added=".$var['date_added'];
			mysqli_query($con, $query);
		}
		mysqli_close($con);
	}

	function daysDifference($date1, $date2)	{
		$date1 = strtotime($date1);
		$date2 = strtotime($date2);
		$datediff = $date2 - $date1;
		return round($datediff / (60 * 60 * 24));
	}


?>
