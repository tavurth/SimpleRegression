/*

  Copyright 2015 William Whitty
  will.whitty.arbeit@gmail.com

  Licensed under the Apache License, Version 2.0 (the 'License');
  you may not use this file except in compliance with the License.
  You may obtain a copy of the License at

  http://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an 'AS IS' BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License.

*/

class Regression {
    protected $aVal, $bVal;
    protected $tableR, $tableT;
		
    public function __construct() {
        $this->reset();
    }
		
    public function update() {
        if (count($this->tableR) < 10)
            return;
			
        $n = count($this->tableR);
			
        //Offset of the linear regression
        $this->aVal = (($this->tableT['y']*$this->tableT['x2'])-($this->tableT['x']*$this->tableT['xy'])) 
                    /*	----------------------- */ / /*	----------------------- */ 
                    (($n*$this->tableT['x2'])-pow($this->tableT['x'],2));
			
        //Gradient of the linear regression
        $this->bVal = (($n*$this->tableT['xy'])-($this->tableT['x']*$this->tableT['y'])) 
                    /*	----------------------- */ / /*	----------------------- */
                    (($n*$this->tableT['x2'])-pow($this->tableT['x'],2));
    }
		
    public function reset() {
        $this->tableT = array('x' => 0, 'y' => 0, 'xy' => 0, 'x2' => 0, 'y2' => 0);
        $this->tableR = array();
    }

    public function add($value, $update=TRUE) {
        $row = array();
        $row['x'] = count($this->tableR)+1;
        $row['y'] = $value;
        $row['xy'] = $row['x']*$row['y'];
        $row['x2'] = pow($row['x'], 2);
        $row['y2'] = pow($row['y'], 2);
			
        foreach ($row as $key => $value)
            $this->tableT[$key] += $value;
			
        $this->tableR[] = $row;
		
        if ($update) $this->update();
    }
		
    public function add_array(array $values) {
        foreach ($values as $value)
            $this->add($value, FALSE);
        $this->update();
    }
		
    public function pos($x) {
        return $this->aVal + ($this->bVal * $x);
    }
		
    public function std_dev() { 
        $mean = 0;
        foreach ($this->tableR as $set)
            $mean += $set['y'];
        $mean /= count($this->tableR);
			
        $sum = 0;
        foreach ($this->tableR as $set)
            $sum += pow($set['y'] - $mean, 2);
			
        return sqrt($sum / (count($this->tableR)-1));
    }
		
    public function a_val() { return (isset($this->aVal)) ? $this->aVal : FALSE; } 
    public function b_val() { return (isset($this->bVal)) ? $this->bVal : FALSE; }
}