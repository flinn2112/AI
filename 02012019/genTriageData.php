<?
    /*
     * 2018 Generiere Testdatensätze f. Triage AI
     */
    class gen{
        public $m_rData = array() ; //assoc Array
        public $m_rRow  = array() ;
        
        /*
         * Plausi anwenden:
         * An Weihnachten kann nicht Ostern sein, oder so was...
         * !nicht so viele bewusstlose zulassen
         */
        private function adjust(&$rRow){
            
        }
        /*
         * generate the level 4 pat
         */
        public function genAverageJoe($nRows){            
            for($i = 0; $i < $nRows;$i++){
                $this->m_rRow['ALTER'] = mt_rand(25, 65) ;
                $this->m_rRow['GESCHLECHT'] = mt_rand(1, 3) ;
                $this->m_rRow['SCHMERZ'] = mt_rand(1, 4) ;
                $this->m_rRow['LOKALISATION'] = mt_rand(1, 32) ;
                $this->m_rRow['TEMPERATUR'] = mt_rand(35, 38) ;
                $this->m_rRow['WOCHENTAG'] = mt_rand(1, 7) ; //pop us Friday till Sunday
                $this->m_rRow['TAGESZEIT'] = mt_rand(1, 3) ;  //can enter any time, but never at night(4)
                $this->m_rRow['FEIERTAG'] = mt_rand(0, 1) ;
                $this->m_rRow['BLUTUNG'] = mt_rand(0, 3) ; //may bleed a little                
                $this->m_rRow['ATMUNG'] = mt_rand(0, 2) ; //short of breath a little                
                $this->m_rRow['PULS'] = mt_rand(60, 80) ;
                $this->m_rRow['KRANKHEITSDAUER'] = mt_rand(2, 7) ;
                $this->m_rRow['BEWUSSTSEIN'] = 1 ; //of course
                array_push($this->m_rData, $this->m_rRow) ;
            }
        }
        
        /*
         * generate the level 4 pat
         */
        public function genHypochondriac($nRows){            
            for($i = 0; $i < $nRows;$i++){
                $this->m_rRow['ALTER'] = mt_rand(17, 65) ;
                $this->m_rRow['GESCHLECHT'] = mt_rand(1, 2) ;
                $this->m_rRow['SCHMERZ'] = mt_rand(0, 2) ;
                $this->m_rRow['LOKALISATION'] = mt_rand(1, 32) ;
                $this->m_rRow['TEMPERATUR'] = mt_rand(36, 38) ;
                $this->m_rRow['WOCHENTAG'] = mt_rand(5, 7) ; //pop us Friday till Sunday
                $this->m_rRow['TAGESZEIT'] = mt_rand(1, 2) ;  //never pops up late
                $this->m_rRow['FEIERTAG'] = mt_rand(0, 1) ;
                $this->m_rRow['BLUTUNG'] = 0 ; //              
                $this->m_rRow['ATMUNG'] = 0 ; //short of breath a little                
                $this->m_rRow['PULS'] = mt_rand(60, 80) ;
                $this->m_rRow['KRANKHEITSDAUER'] = mt_rand(2, 7) ;
                $this->m_rRow['BEWUSSTSEIN'] = 1 ; //of course
                array_push($this->m_rData, $this->m_rRow) ;
            }
        }
        
        public function isHypochondriac($row){
            return (
                ($row['ALTER'] >= 17       && $row['ALTER'] <= 65) &&
                ($row['SCHMERZ'] >= 0      && $row['SCHMERZ'] >= 4) &&
                ($row['TEMPERATUR'] >= 36  && $row['TEMPERATUR'] >= 38) &&
                ($row['WOCHENTAG'] >= 5    && $row['WOCHENTAG'] <= 7) &&
                ($row['TAGESZEIT'] >= 9      && $row['TAGESZEIT'] <= 19) &&
                ($row['BLUTUNG'] >= 0      && $row['BLUTUNG'] <= 3) &&
                ($row['ATMUNG'] >=  0      && $row['ATMUNG'] <=  2)  &&
                ($row['PULS'] >= 60        && $row['PULS'] <= 80)  &&
                $row['BEWUSSTSEIN'] = 1    && //ist bei Bewusstsein
                $row['KRANKHEITSDAUER'] >= 2
            ) ;
        }
        
        public function isAverageJoe($row){
            return (                
                ($row['SCHMERZ'] >= 0     && $row['SCHMERZ'] <= 3) &&
                ($row['TEMPERATUR'] >= 36 && $row['TEMPERATUR'] >= 38) &&                
                ($row['BLUTUNG'] >= 0     && $row['BLUTUNG'] <= 3) &&
                ($row['ATMUNG'] >=  0     && $row['ATMUNG'] <=  2)  &&
                ($row['PULS'] >= 60       && $row['PULS'] <= 80)  &&
                $row['BEWUSSTSEIN'] = 1
            ) ;
        }
        
        public function isLevel4($row){
            return (                
                ($row['SCHMERZ'] == 0     && $row['SCHMERZ'] == 1) &&
                ($row['TEMPERATUR'] >= 36 && $row['TEMPERATUR'] >= 38) && //normale Temp
                ($row['TAGESZEIT'] >= 1     && $row['TAGESZEIT'] <= 3) &&
                ($row['BLUTUNG'] >= 0     && $row['BLUTUNG'] <= 2) &&
                ($row['ATMUNG'] >=  0     && $row['ATMUNG'] <=  2)  &&
                ($row['PULS'] >= 60       && $row['PULS'] <= 80)  &&
                $row['BEWUSSTSEIN'] = 1 &&
                $row['KRANKHEITSDAUER'] >= 2
            ) ;
        }
        
        
        
        public function generate($nRows){
            $iBewusstlos = 0 ;
            $iSchmerz    = 0 ;
            $iTemp       = 0 ;
            $iBlut       = 0 ;
            $iAtmung     = 0 ;
            for($i = 0; $i < $nRows;$i++){
                $this->m_rRow['ALTER'] = mt_rand(1, 99) ;
                $this->m_rRow['GESCHLECHT'] = mt_rand(1, 3) ;
                
                 $this->m_rRow['BEWUSSTSEIN'] = mt_rand(0, 1) ;  //0 == bewusstlos
                if($this->m_rRow['BEWUSSTSEIN'] == 0) $iBewusstlos++ ;
                if($this->m_rRow['BEWUSSTSEIN'] == 0 && $iBewusstlos > ($nRows/10)){
                    $this->m_rRow['BEWUSSTSEIN'] = 1 ; //zu viele Bewusstlose -> doch bei bewusstsein
                }
                if($this->m_rRow['BEWUSSTSEIN'] == 0){
                    $this->m_rRow['SCHMERZ'] = 0 ;
                }
                else{
                    $this->m_rRow['SCHMERZ'] = mt_rand(1, 10) ;             
                    if($this->m_rRow['SCHMERZ'] > 1) $iSchmerz++ ;
                    if($this->m_rRow['SCHMERZ'] > 1 && $iSchmerz > ($nRows/60)){ //nur ein 70tel sollen Schmerzpatienten sein  
                        $this->m_rRow['SCHMERZ'] = 0 ; //ohne Schmerz
                    }
                    if( $this->m_rRow['SCHMERZ'] == 0 ) //wenn kein Schmerz, dann auch nicht WO.
                        $this->m_rRow['LOKALISATION'] = 0 ;
                    else
                        $this->m_rRow['LOKALISATION'] = mt_rand(1, 32) ;
                }
                $this->m_rRow['TEMPERATUR'] = mt_rand(27, 41) ;
                if($this->m_rRow['TEMPERATUR'] > 37 || $this->m_rRow['TEMPERATUR'] < 35 ) $iTemp++ ;
                if( ($this->m_rRow['TEMPERATUR'] > 36 || $this->m_rRow['TEMPERATUR'] < 35 ) && $iTemp > ($nRows/50)){ //nur ein 60tel sollen unregelmässige Temperatur haben.
                    $this->m_rRow['TEMPERATUR'] = 36 ; //normal
                }
                $this->m_rRow['WOCHENTAG'] = mt_rand(1, 7) ;
                $this->m_rRow['TAGESZEIT'] = mt_rand(1, 4) ; //->morgens, mittags, abends, nachts
                $this->m_rRow['FEIERTAG'] = mt_rand(0, 1) ;
                $this->m_rRow['BLUTUNG'] = mt_rand(0, 10) ;
                if($this->m_rRow['BLUTUNG'] > 1) $iBlut++ ;
                if($this->m_rRow['BLUTUNG'] > 1 && $iBlut > ($nRows/80)){ 
                    $this->m_rRow['BLUTUNG'] = 0 ; 
                }
                $this->m_rRow['ATMUNG'] = mt_rand(0, 10) ;
                if($this->m_rRow['ATMUNG'] > 0) $iAtmung++ ;
                if($this->m_rRow['ATMUNG'] > 1 && $iAtmung > ($nRows/90)){ 
                    $this->m_rRow['ATMUNG'] = 0 ; 
                }
                $this->m_rRow['PULS'] = mt_rand(40, 200) ;
                $this->m_rRow['KRANKHEITSDAUER'] = mt_rand(0, 7) ;
               
                $this->adjust($this->m_rRow) ;
                array_push($this->m_rData, $this->m_rRow) ;
            }            
        }
        
        
        /*
         * Klassifizieren von 1-5
         * Erstmal einfache Regeln anwenden.
         */
        public function classify(){
            foreach($this->m_rData as &$this->m_rRow){                
                $this->m_rRow['EINSTUFUNG'] = 5 ;
                
                //wir brauchen die 4 und 5 Kategorie
                if($this->isAverageJoe($this->m_rRow)){                    
                    $this->m_rRow['EINSTUFUNG'] = 3 ;
                    continue ;
                }
                if($this->isHypochondriac($this->m_rRow)){                    
                    $this->m_rRow['EINSTUFUNG'] = 5 ;
                    continue ;
                }
                
                if($this->isLevel4($this->m_rRow)){                    
                    $this->m_rRow['EINSTUFUNG'] = 4 ;
                    continue ;
                }
                
                
                
                if($this->m_rRow['BEWUSSTSEIN'] == 0 ){
                    $this->m_rRow['EINSTUFUNG'] = 1 ; //abbrechen - höchste Stufe
                    continue ;
                }
                
                if($this->m_rRow['TEMPERATUR'] > 39 ){
                    $this->m_rRow['EINSTUFUNG'] = 2 ;
                }
                
                if($this->m_rRow['TEMPERATUR'] < 34 ){
                    $this->m_rRow['EINSTUFUNG'] = 2 ;
                }
                
                if($this->m_rRow['BLUTUNG'] > 3 ){
                    $this->m_rRow['EINSTUFUNG'] = 2 ; //aber weiter klassifizieren
                }
                if($this->m_rRow['BLUTUNG'] > 5 ){
                    $this->m_rRow['EINSTUFUNG'] = 1 ; //abbrechen - höchste Stufe
                    continue ;
                }
                
                if($this->m_rRow['SCHMERZ'] > 3 ){
                    $this->m_rRow['EINSTUFUNG'] = 2 ; //aber weiter klassifizieren
                }
                if($this->m_rRow['SCHMERZ'] > 5 ){
                    $this->m_rRow['EINSTUFUNG'] = 1 ; //abbrechen - höchste Stufe
                    continue ;
                }
                
                if($this->m_rRow['ATMUNG'] > 2 ){
                    $this->m_rRow['EINSTUFUNG'] = 2 ; //aber weiter klassifizieren
                }
                if($this->m_rRow['ATMUNG'] > 3 ){
                    $this->m_rRow['EINSTUFUNG'] = 1 ; //abbrechen - höchste Stufe
                    continue ;
                }
                
                if($this->m_rRow['ALTER'] < 12 && $this->m_rRow['TEMPERATUR'] > 38 ){
                    $this->m_rRow['EINSTUFUNG'] = 2 ; //aber weiter klassifizieren
                }
                if($this->m_rRow['ATMUNG'] > 3  && $this->m_rRow['TEMPERATUR'] > 39 ){
                    $this->m_rRow['EINSTUFUNG'] = 1 ; //abbrechen - höchste Stufe
                    continue ;
                }
                
                if($this->m_rRow['ALTER'] > 40  && $this->m_rRow['LOKALISATION'] == 7 ){ //Herz
                    $this->m_rRow['EINSTUFUNG'] = 2 ; //aber weiter klassifizieren
                }
                if($this->m_rRow['ALTER'] > 40  && $this->m_rRow['LOKALISATION'] == 7 && $this->m_rRow['SCHMERZ'] > 3 ){
                    $this->m_rRow['EINSTUFUNG'] = 1 ; //abbrechen - höchste Stufe
                    continue ;
                }
                //jetzt noch bisschen auf 2,3,4 verteilen.
                //Einstufung ist geblieben - aber Patient hat schon Länger beschwerden
                if( 5 == $this->m_rRow['EINSTUFUNG'] && $this->m_rRow['KRANKHEITSDAUER'] > 2 ){
                    $this->m_rRow['EINSTUFUNG'] = 4 ; //aber weiter klassifizieren
                }
                if($this->m_rRow['SCHMERZ'] == 2 ){
                    $this->m_rRow['EINSTUFUNG'] = 3 ; //aber weiter klassifizieren
                }
                if( 5 == $this->m_rRow['EINSTUFUNG'] && $this->m_rRow['BLUTUNG'] == 1 ){
                    $this->m_rRow['EINSTUFUNG'] = 2 ; //aber weiter klassifizieren
                }
                if(  $this->m_rRow['EINSTUFUNG'] >= 3 && $this->m_rRow['TEMPERATUR'] < 35 ){
                    $this->m_rRow['EINSTUFUNG'] = 2 ; //aber weiter klassifizieren
                }
                if( $this->m_rRow['EINSTUFUNG'] >= 3 && $this->m_rRow['TEMPERATUR'] > 38 ){
                    $this->m_rRow['EINSTUFUNG'] = 3 ; //aber weiter klassifizieren
                }
                if( $this->m_rRow['EINSTUFUNG'] >= 3 && $this->m_rRow['TEMPERATUR'] > 38 && $this->m_rRow['ALTER'] < 14 ){
                    $this->m_rRow['EINSTUFUNG'] = 2 ; 
                }
                if( $this->m_rRow['TEMPERATUR'] < 32 ){
                    $this->m_rRow['EINSTUFUNG'] = 2 ; 
                }
                
            }
        }
        
        public function dump(){
            var_dump($this->m_rData) ;
        }
        /*
         * es wird auf 24 spalten aufgefüllt
         */
        public function toFile($strName, $cDelimiter){
            $strDataFilename = sprintf("%s_data.csv", $strName) ;
            $strResultFilename = sprintf("%s_result.csv", $strName) ;
            unlink ( $strDataFilename ) ;
            unlink ( $strResultFilename ) ;
            print("<br>") ;
            foreach($this->m_rData as $this->m_rRow){
                $strOut = sprintf( "%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,"
                        .          "%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,"
                        .          "%s.0,%s.0,%s.0,%s.0\n",
                        $this->m_rRow['ALTER'],
                        $this->m_rRow['GESCHLECHT'],
                        $this->m_rRow['BEWUSSTSEIN'],
                        $this->m_rRow['ATMUNG'],
                        $this->m_rRow['TEMPERATUR'],
                        $this->m_rRow['BLUTUNG'],
                        $this->m_rRow['PULS'],                        
                        $this->m_rRow['SCHMERZ'],
                        //nehme mal die nicht so relevanten Daten auf 0
                        $this->m_rRow['LOKALISATION'],                        
                        $this->m_rRow['WOCHENTAG'],
                        $this->m_rRow['TAGESZEIT'],
                        $this->m_rRow['FEIERTAG'],
                        $this->m_rRow['KRANKHEITSDAUER'],                        
                        1.0,1.0,1.0,1.0,1.0,1.0,1.0,1.0,1.0,1.0,1);
                printf("%s<br>", $strOut) ;
                $strOut = preg_replace("/,/", $cDelimiter, $strOut) ;
                
                file_put_contents($strDataFilename, $strOut, FILE_APPEND) ;
                //das Ergebnisfile wird aus der Einstufungsspalte erstellt.
                switch($this->m_rRow['EINSTUFUNG']){
                 case 1:
                     $strOut = "1.0#0.0#0.0#0.0#0.0\n" ;
                     break ;
                 case 2:
                     $strOut = "0.0#1.0#0.0#0.0#0.0\n" ;
                     break ;
                 case 3:
                     $strOut = "0.0#0.0#1.0#0.0#0.0\n" ;
                     break ;
                 case 4:
                     $strOut = "0.0#0.0#0.0#1.0#0.0\n" ;
                     //$strOut = "0.0#1#0.0#0.0#0\n" ;//nur test
                     break ;
                 case 5:
                     $strOut = "0.0#0.0#0.0#0.0#1.0\n" ;
                     //$strOut = "0.0#1#0.0#0.0#0\n" ;//nur test
                     break ;
                 default:       
                     $strOut = "0.0#0.0#0.0#0.0#1.0\n" ;
                     //$strOut = "0#1#0#0#0\n" ; //nur test
                }
                $strOut = preg_replace("/#/", $cDelimiter, $strOut) ;
                printf("%s<br>", $strOut) ;
                file_put_contents($strResultFilename, $strOut, FILE_APPEND) ;
                
            }
        }
        
        
    }
    
    $nRows = $_GET['ROWS'] ;
    if(0 == $nRows) $nRows = 2500 ; 
    $oGen = new Gen() ;
    $oGen->genAverageJoe($nRows/5) ; //20%
    $oGen->genHypochondriac($nRows/10) ; //10%
    $oGen->generate($nRows - $nRows/5 - $nRows/10) ;
    $oGen->classify() ;
    $oGen->dump() ;
    $oGen->toFile("training", " ") ;
    $nRows = $nRows / 20 ; //Testdaten sind damit deutlich weniger als Trainingsdaten
    $oGen = new Gen() ;
    $oGen->genAverageJoe($nRows/5) ; //20%
    $oGen->genHypochondriac($nRows/10) ; //10%
    $oGen->generate($nRows - $nRows/5 - $nRows/10) ;
    $oGen->classify() ;
    $oGen->dump() ;
    $oGen->toFile("test", " ") ;
?>
