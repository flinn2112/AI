<?
    define("__HYPOCHONDRIAC__", 6) ;
    define("__AVERAGE_JOE__", 5) ;
    
    define("__NEEDY__",  2) ;
    define("__LEVEL2__", 2) ;
    define("__URGENT__", 1) ;
    define("__LEVEL1__", 1) ;
    define("__LEVEL3__", 3) ;
    define("__LEVEL4__", 4) ;
    define("__LEVEL5__", 4) ;
    
    
    /*
     * 2018 Generiere Testdatensätze f. Triage AI
     */
    class gen{
        public $m_rData = array() ; //assoc Array
        public $m_rRow  = array() ;
        private $m_DEBUG = null ;
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
                $this->m_rRow['FEIERTAG'] = mt_rand(1, 2) ;
                $this->m_rRow['BLUTUNG'] = mt_rand(1, 3) ; //may bleed a little                
                $this->m_rRow['ATMUNG'] = mt_rand(1, 2) ; //short of breath a little                
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
                $this->m_rRow['FEIERTAG'] = mt_rand(1, 2) ;
                $this->m_rRow['BLUTUNG'] = 1 ; //              
                $this->m_rRow['ATMUNG'] = 1 ; //short of breath a little                
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
                ($row['SCHMERZ'] >= 1     && $row['SCHMERZ'] <= 3) &&
                ($row['TEMPERATUR'] >= 36 && $row['TEMPERATUR'] >= 38) &&                
                ($row['BLUTUNG'] >= 1     && $row['BLUTUNG'] <= 3) &&
                ($row['ATMUNG'] >=  0     && $row['ATMUNG'] <=  2)  &&
                ($row['PULS'] >= 60       && $row['PULS'] <= 80)  &&
                $row['BEWUSSTSEIN'] = 1
            ) ;
        }
        
        public function isLevel4($row){
            return (                
                ($row['SCHMERZ'] == 1     && $row['SCHMERZ'] == 2) &&
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
            $bDebug      = false ;
            for($i = 0; $i < $nRows;$i++){
                $this->m_rRow['ALTER'] = mt_rand(1, 99) ;
                $this->m_rRow['GESCHLECHT'] = mt_rand(1, 3) ;
                
                 $this->m_rRow['BEWUSSTSEIN'] = mt_rand(1, 2) ;  //2 == bewusstlos
                if($this->m_rRow['BEWUSSTSEIN'] == 2) $iBewusstlos++ ;
                if($this->m_rRow['BEWUSSTSEIN'] == 2 && $iBewusstlos > ($nRows/3)){ //nur max etwa 30% zulassen. 
                    $this->m_rRow['BEWUSSTSEIN'] = 1 ; //zu viele Bewusstlose -> doch bei bewusstsein
                }
                if($this->m_rRow['BEWUSSTSEIN'] == 2){
                    $this->m_rRow['SCHMERZ'] = 1 ;
                }
                else{
                    $this->m_rRow['SCHMERZ'] = mt_rand(1, 10) ;             
                    if($this->m_rRow['SCHMERZ'] > 1) $iSchmerz++ ;
                    if($this->m_rRow['SCHMERZ'] > 1 && $iSchmerz > ($nRows/60)){ //nur ein 70tel sollen Schmerzpatienten sein  
                        $this->m_rRow['SCHMERZ'] = 1 ; //ohne Schmerz
                    }
                    if( $this->m_rRow['SCHMERZ'] == 1 ) //wenn kein Schmerz, dann auch nicht WO.
                        $this->m_rRow['LOKALISATION'] = 1 ;
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
                $this->m_rRow['FEIERTAG'] = mt_rand(1, 2) ;
                $this->m_rRow['BLUTUNG'] = mt_rand(1, 10) ;
                if($this->m_rRow['BLUTUNG'] > 1) $iBlut++ ;
                if($this->m_rRow['BLUTUNG'] > 1 && $iBlut > ($nRows/80)){ 
                    $this->m_rRow['BLUTUNG'] = 1 ; 
                }
                $this->m_rRow['ATMUNG'] = mt_rand(1, 10) ;
                if($this->m_rRow['ATMUNG'] > 1) $iAtmung++ ;
                if($this->m_rRow['ATMUNG'] > 1 && $iAtmung > ($nRows/90)){ 
                    $this->m_rRow['ATMUNG'] = 1 ; 
                }
                $this->m_rRow['PULS'] = mt_rand(40, 200) ;
                //jemand der stark blutet kann das nicht schon lange haben usw.       
                if( $this->m_rRow['BLUTUNG'] > 1 || $this->m_rRow['ATMUNG'] >3  || $this->m_rRow['SCHMERZ'] >3
                        || $this->m_rRow['BEWUSSTSEIN'] == 2){ //bewusstloss                
                    $this->m_rRow['KRANKHEITSDAUER'] = 1 ;
                }
                else{
                    $this->m_rRow['KRANKHEITSDAUER'] = mt_rand(1, 7) ;
                }
                $this->adjust($this->m_rRow) ;
                array_push($this->m_rData, $this->m_rRow) ;
            }            
        }
        
        /*
         * Testmethode f. Evaluierung einzelner Resultate.
         */
        public function evalVector($strValues, $cDelimiter=" "){ //sind Werte getrennt durch Leerzeichen o.ä
            $rValues = explode ( $cDelimiter, $strValues) ;
            print_r($rValues) ;            
            $this->m_rRow['ALTER']  = $rValues[0] ;
            $this->m_rRow['GESCHLECHT'] = $rValues[1] ;
            $this->m_rRow['BEWUSSTSEIN'] = $rValues[2] ;
            $this->m_rRow['ATMUNG'] = $rValues[3] ;
            $this->m_rRow['TEMPERATUR'] = $rValues[4] ;
            $this->m_rRow['BLUTUNG'] = $rValues[5] ;
            $this->m_rRow['PULS'] = $rValues[6] ;       
            $this->m_rRow['SCHMERZ'] = $rValues[7] ;
            //nehme mal die nicht so relevanten Daten auf 0
            $this->m_rRow['LOKALISATION'] = $rValues[8] ;               
            $this->m_rRow['WOCHENTAG'] = $rValues[9] ;
            $this->m_rRow['TAGESZEIT'] = $rValues[10] ;
            $this->m_rRow['FEIERTAG'] = $rValues[11] ;
            $this->m_rRow['KRANKHEITSDAUER'] = $rValues[12] ;
            print("<br>") ;
            foreach(array_keys($this->m_rRow) as $key){
                printf("%s -> %s<br>", $key, $this->m_rRow[$key]) ;
            }
            $this->bDebug = true ;
            array_push($this->m_rData, $this->m_rRow) ;
            $this->classify(1) ;
            print("<br>") ;
            print_r($this->m_rRow) ;
        }
        /*
         * Klassifizieren von 1-5
         * Erstmal einfache Regeln anwenden.
         */
        public function classify(){
            
            printf("DEBUG is [%s]<br>", $this->m_DEBUG) ;
            
            foreach($this->m_rData as &$this->m_rRow){                
                $this->m_rRow['EINSTUFUNG'] = 5 ;
                if( $this->m_DEBUG ){
                    print("<br>") ;
                    $rElements = array_keys($this->m_rRow) ;
                    for($i=0;$i<sizeof($rElements);$i++){
                        printf("[%s] = [%d] <br>", $rElements[$i], $this->m_rRow[$rElements[$i]]) ;
                    }
                    print("<br>") ;
                }
                //wir brauchen die 4 und 5 Kategorie
                if($this->isAverageJoe($this->m_rRow)){                    
                    $this->m_rRow['EINSTUFUNG'] = 3 ;
                    if( $this->m_DEBUG ){                        
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "__AVERAGE_JOE__", __LINE__) ;
                    }
                    continue ;
                }
                if($this->isHypochondriac($this->m_rRow)){                    
                    $this->m_rRow['EINSTUFUNG'] = 5 ;
                    if( $this->m_DEBUG ){
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "__HYPOCHONDER__", __LINE__) ;
                    }
                    continue ;
                }
                
                if($this->isLevel4($this->m_rRow)){                    
                    $this->m_rRow['EINSTUFUNG'] = 4 ;
                    if( $this->m_DEBUG ){
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "LEVEL_4", __LINE__) ;
                    }
                    continue ;
                }
                
                if($this->m_rRow['BEWUSSTSEIN'] == 2 ){
                    $this->m_rRow['EINSTUFUNG'] = 1 ; //abbrechen - höchste Stufe
                    if( $this->m_DEBUG ){
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "BEWUSSTSEIN", __LINE__) ;
                    }
                    continue ;
                }
                
                if($this->m_rRow['TEMPERATUR'] > 39 ){
                    $this->m_rRow['EINSTUFUNG'] = 2 ;
                    if( true == $this->bDebug ){
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['TEMPERATUR'], "TEMPERATUR > 39", __LINE__) ;
                    }
                }
                
                if($this->m_rRow['TEMPERATUR'] < 34 ){
                    $this->m_rRow['EINSTUFUNG'] = 2 ;
                    if( true == $this->bDebug ){
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['TEMPERATUR < 34'], "LEVEL_4", __LINE__) ;
                    }
                }
                
                if($this->m_rRow['BLUTUNG'] > 3 ){
                    $this->m_rRow['EINSTUFUNG'] = 2 ; //aber weiter klassifizieren
                    if( $bDebug ){
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "BLUTUNG > 3", __LINE__) ;
                    }
                }
                if($this->m_rRow['BLUTUNG'] > 5 ){
                    $this->m_rRow['EINSTUFUNG'] = 1 ; //abbrechen - höchste Stufe
                    if( $this->m_DEBUG ){
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "BLUTUNG > 5", __LINE__) ;
                    }
                    continue ;
                }
                
                if($this->m_rRow['SCHMERZ'] > 3 ){
                    $this->m_rRow['EINSTUFUNG'] = 2 ; //aber weiter klassifizieren
                    if( true == $this->bDebug ){
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "SCHMERZ > 3", __LINE__) ;
                    }
                }
                if($this->m_rRow['SCHMERZ'] > 5 ){
                    $this->m_rRow['EINSTUFUNG'] = 1 ; //abbrechen - höchste Stufe
                    if( $this->m_DEBUG ){
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "SCHMERZ > 5", __LINE__) ;
                    }
                    continue ;
                }
                
                if($this->m_rRow['ATMUNG'] > 2 ){
                    $this->m_rRow['EINSTUFUNG'] = 2 ; //aber weiter klassifizieren
                    if( $this->m_DEBUG ){
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "ATMUNG > 2", __LINE__) ;
                    }
                }
                if($this->m_rRow['ATMUNG'] > 3 ){
                    $this->m_rRow['EINSTUFUNG'] = 1 ; //abbrechen - höchste Stufe
                    if( $bDebug ){
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "ATMUNG > 3", __LINE__) ;
                    }
                    continue ;
                }
                
                if($this->m_rRow['ALTER'] < 12 && $this->m_rRow['TEMPERATUR'] > 38 ){
                    $this->m_rRow['EINSTUFUNG'] = 2 ; //aber weiter klassifizieren
                    if( true == $this->bDebug ){
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "ALTER < 12 && TEMPERATUR > 38", __LINE__) ;
                    }
                }
                if($this->m_rRow['ATMUNG'] > 3  && $this->m_rRow['TEMPERATUR'] > 39 ){
                    $this->m_rRow['EINSTUFUNG'] = 1 ; //abbrechen - höchste Stufe
                    if( $this->m_DEBUG ){
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "ATMUNG > 3 && TEMPERATUR > 39", __LINE__) ;
                    }
                    continue ;
                }
                
                if($this->m_rRow['ALTER'] > 40  && $this->m_rRow['LOKALISATION'] == 7 ){ //Herz
                    $this->m_rRow['EINSTUFUNG'] = 2 ; //aber weiter klassifizieren
                    if( $this->m_DEBUG ){
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "ALTER > 40 && LOKALISATION = HERZ", __LINE__) ;
                    }
                }
                if($this->m_rRow['ALTER'] > 40  && $this->m_rRow['LOKALISATION'] == 7 && $this->m_rRow['SCHMERZ'] > 3 ){
                    $this->m_rRow['EINSTUFUNG'] = 1 ; //abbrechen - höchste Stufe
                    if( $this->m_DEBUG ){
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "ALTER > 40 && LOKALISATION = HERZ && SCHMERZ > 3", __LINE__) ;
                    }
                    continue ;
                }
                //jetzt noch bisschen auf 2,3,4 verteilen.
                //Einstufung ist geblieben - aber Patient hat schon Länger beschwerden
                if( 5 == $this->m_rRow['EINSTUFUNG'] && $this->m_rRow['KRANKHEITSDAUER'] > 2 ){
                    $this->m_rRow['EINSTUFUNG'] = 4 ; //aber weiter klassifizieren
                    if( $this->m_DEBUG ){
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "KRANKHEITSDAUER > 2", __LINE__) ;
                    }
                }
                if($this->m_rRow['SCHMERZ'] == 2 ){
                    $this->m_rRow['EINSTUFUNG'] = 3 ; //aber weiter klassifizieren
                    if( $this->m_DEBUG ){
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "SCHMERZ = 2", __LINE__) ;
                    }
                }
                //Leichte Blutung
                if( 5 == $this->m_rRow['EINSTUFUNG'] && $this->m_rRow['BLUTUNG'] == 2 ){
                    $this->m_rRow['EINSTUFUNG'] = 2 ; //aber weiter klassifizieren
                    if( $this->m_DEBUG ){
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "BLUTUNG = 2", __LINE__) ;
                    }
                }
                if(  $this->m_rRow['EINSTUFUNG'] >= 3 && $this->m_rRow['TEMPERATUR'] < 35 ){
                    $this->m_rRow['EINSTUFUNG'] = 2 ; //aber weiter klassifizieren
                    if( $this->m_DEBUG ){
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "TEMPERATUR < 35", __LINE__) ;
                    }
                }
                if( $this->m_rRow['EINSTUFUNG'] >= 3 && $this->m_rRow['TEMPERATUR'] > 38 ){
                    $this->m_rRow['EINSTUFUNG'] = 3 ; //aber weiter klassifizieren
                    if( $bDebug ){
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "ALTER < 12 && TEMPERATUR > 38", __LINE__) ;
                    }
                }
                
                if( $this->m_rRow['TEMPERATUR'] < 32 ){
                    $this->m_rRow['EINSTUFUNG'] = 2 ; 
                    if( $this->m_DEBUG ){
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "TEMPERATUR < 32", __LINE__) ;
                    }
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
            $strDataFilename   = sprintf("%s_data.csv", $strName) ;
            //$strResultFilename = sprintf("%s_result.csv", $strName) ;
            $strResult         = null ;
            unlink ( $strDataFilename ) ;
            //unlink ( $strResultFilename ) ;
            print("<br>") ;
            foreach($this->m_rData as $this->m_rRow){
                switch($this->m_rRow['EINSTUFUNG']){
                 case 1:
                     $strResult = "0.9,.1,0.0,0.0,0.0" ;
                     break ;
                 case 2:
                     $strResult = "0.0,0.9,0.0,0.0,0.0" ;
                     break ;
                 case 3:
                     $strResult = "0.0,0.0,0.9,0.0,0.0" ;
                     break ;
                 case 4:
                     $strResult = "0.0,0.0,0.0,0.9,0.0" ;
                     
                     break ;
                 case 5:
                     $strResult = "0.0,0.0,0.0,0.0,0.9" ;
                     
                     break ;
                 default:       
                     $strResult = "0.0,0.0,0.0,0.0,0.9" ;
                     
                }
                
                
                
                $strOut = sprintf( "%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,"
                        .          "%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,"
                        .          "%s.0,%s.0,%s.0,%s.0,%s\n",
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
                        1.0,1.0,1.0,1.0,1.0,1.0,1.0,1.0,1.0,1.0,1,
                        $strResult);
                
                $strOut = preg_replace("/,/", $cDelimiter, $strOut) ;
                printf("%s<br>", $strOut) ;
                file_put_contents($strDataFilename, $strOut, FILE_APPEND) ;
                //das Ergebnisfile wird aus der Einstufungsspalte erstellt.
                
                //$strOut = preg_replace("/#/", $cDelimiter, $strOut) ;
                //printf("%s<br>", $strOut) ;
                // obsolet(alles wird jetzt in ein File gespeichert): file_put_contents($strResultFilename, $strOut, FILE_APPEND) ;
                
            }
        }
    }
    
     class MTS_PAIN_VALUES {
        const SEVERE   = 3;
        const MODERATE = 2;
        const MILD     = 1 ;
        public static function getRand(){
            return mt_rand(1,3) ;
        }
    }
    
    class MTS_TEMPERATURE_VALUES {
        const VERY_HOT = 4 ;
        const HOT      = 3 ;
        const WARM     = 2 ;  
        const COLD     = 1 ;
        public static function getRand(){
            return mt_rand(1,4) ;
        }
        public static function getNormal(){
            return mt_rand(1,2) ;
        }
    }
    //nachträglich dazugekommen f. HTML Dialog.
    class MTSItem{
        public static $arrayIDX = 0 ;
        public $ID       = "" ;
        public $text     = "" ;
        public $language = "" ;
        public $type     = "" ;
        
        
        public function MTSItem($strID, $strText, $strLanguage, $strType){
             
             $this->ID        = $strID ;
             $this->text      = $strText ;
             $this->language  = $strLanguage ;
             $this->type      = $strType ;
             $this->iIDX      = MTSItem::$arrayIDX ;
             MTSItem::$arrayIDX++ ;
        }
        
        private function _T($strID, $strDefault, $strLanguage){        
            return $strDefault ;
        }
        
        public function getDisplayText(){
              return  $this->_T($this->ID, $this->text, $this->language) ;
        }
    }
    //nachträglich - das ist erstmal doppelt
    class MTSAttributeDirectory{
        const HCC_GENDER                        = "HCC_GENDER" ;
        const HCC_UNCONSCIOUS                   = "HCC_UNCONSCIOUS" ;
        const HCC_DAYTIME                       = "HCC_DAYTIME" ; //morgens, mittags ... 1-4
        const HCC_RECURRING                     = 'HCC_RECURRING' ;
        const HCC_PRIVATE_INSURANCE             = 'HCC_PRIVATE_INSURANCE' ;
        const HCC_AGE                           = 'HCC_AGE' ;        
        const AIRWAY_COMPROMISE                 = "AIRWAY_COMPROMISE";//ab hier Standard MTS
        const INADEQUATE_BREATHING              = "INADEQUATE_BREATHING";
        const EXSANGUATING_HAEMORRHAGE          = "EXSANGUATING_HAEMORRHAGE";
        const UNCONTROLLABLE_MAJOR_HAEMORRHAGE  = "UNCONTROLLABLE_MAJOR_HAEMORRHAGE";  //10     
        
        const SHOCK                             = "SHOCK";
        const CURRENTLY_FITTING                 = "CURRENT_FITTING";
        const UNRESPONSIVE_CHILD                = "UNRESPONSIVE_CHILD";
        const STRIDOR                           = "STRIDOR";
        const HYPOGLYCAEMIA                     = "HYPOGLYCAEMIA";
        const ALTERED_CONSCIOUS_LEVEL           = "ALTERED_CONSCIOUS_LEVEL";
        const HOT_ADULT                         = "HOT_ADULT"; 
        const HOT_CHILD                         = "HOT_CHILD";
        const VERY_HOT_ADULT                    = "VERY_HOT_ADULT";
        const FACIAL_OEDEMA                     = "FACIAL_OEDEMA";//20
        
        const OEDEMA_OF_THE_LONGUE              = "OEDEMA_OF THE_LONGUE";
        const UNABLE_TO_TALK_IN_SENTENCES       = "UNABLE_TO_TALK_IN_SENTENCES";
        const MARKED_TACHYCARDIA                = "MARKED_TACHYCARDIA";        
        const SIGNIFICANT_MECHANISM_OF_INJURY   = "SIGNIFICANT_MECHANISM_OF_INJURY";  
        const ABNORMAL_PULSE                    = "ABNORMAL_PULSE";  
        const LOW_SAO2                          = "LOW_SAO2";
        const WIDESPREAD_RASH                   = "WIDESPREAD_RASH";
        const SIGNIFICANT_HISTORY_OF_ALLERGY    = "SIGNIFICANT_HISTORY_OF_ALLERGY" ;
        const HISTORY_OF_ALLERGY                = "HISTORY_OF_ALLERGY"; 
//        const FRACTURE                          = "FRACTURE";   //OPEN         
        const HISTORY_OF_UNCONSCIOUSNESS        = "HISTORY_OF_UNCONSCIOUSNESS" ;//30
        
        const LOCAL_INFLAMATION                 = "LOCAL_INFLAMATION";                   
        const RECENT_PROBLEM                    = "RECENT_PROBLEM" ; //Beschwerden bestehen schon länger //40
        const RECENT_MILD_PAIN_OR_ITCH          = "RECENT_MILD_PAIN_OR_ITCH"; //on/off  
        const SEVERE_PAIN_OR_ITCH               = "SEVERE_PAIN_OR_ITCH"; //on/off  
        const VERY_LOW_SAO2                     = "VERY_LOW_SAO2";
        const DEFORMITY                         = "DEFORMITY";        
        const GROSS_DEFORMITY                   = "GROSS_DEFORMITY";  
        const OPEN_FRACTURE                     = "OPEN_FRACTURE";  
        const SWELLING                          = "SWELLING"; 
        const MODERATE_PAIN_OR_ITCH             = "MODERATE_PAIN_OR_ITCH";  //40
        
        const WARMTH                            = "WARMTH";  
        const COLD                              = "COLD"; 
        const UNSTOPPABLE_MINOR_HAEMORRAGE      = "UNSTOPPABLE_MINOR_HAEMORRAGE";
        const NEW_NEUROLOGICAL_DEFICIT          = "NEW_NEUROLOGICAL_DEFICIT" ;  //44
        
        private $m_rCustom = array( ) ; //hcc::Merkmale
        private $m_rL1 = array() ; //alle kritischen Merkmale
        private $m_rL2 = array() ; //Merkmale, für Level 2
        private $m_rL3 = array() ; //Merkmale, für Level 3
        private $m_rL4 = array() ; //Merkmale, für Level 4
        private $m_rL5 = array() ; //Merkmale, für Level 5
        
        
        //zweispaltige Tabelle
        public function asHtml(){
            $strData = "" ;
            //die hcc values, Alter usw.
            //print_r($this->m_rCustom) ;
            $strData = "<html><head><script src=\"triage_form.js\"></script><head>" ;
            $strData = $strData. "<script>function update(strID, iIDX)\n{ var el = null ; \nvar iEnum = 0 ; \n".
                    "\niEnum = 1 ; \ndo{ \n el = document.getElementById('MTS_' + iEnum) ; \niEnum++; alert(el.id) ;}\n while(el);\n        }</script>";
            
            $strData = $strData. "\n\n\n\n\n\n<!-- START MTS -->\n<table>" ;
            
              $strData = $strData. "<tr><td valign=\"top\"><fieldset style=\"text-align:left\">\n".
                "<legend style=\"padding:7px;text-align:left\">Custom Attributes</legend>" ;  
                //print_r($this->m_rCustom) ;
                foreach($this->m_rCustom as $oMTSItem){
                        //$strData = sprintf("%s%s<br/>", $strData,  $oMTSItem->ID) ;
                  //      printf("TYPE: %s<br/>", $oMTSItem->type) ;
                        $found = preg_match("/numc.*/i", $oMTSItem->type) ;
                        if( 1 == $found ){
                            $strData = $strData. sprintf("<input id=\"MTS_%s\" name=\"%s\" class=\"class_none\"  ".
                                                        " type=\"input\" value=\"1\" onclick=\"javascript:enumValues('%s', %d)\" />\n", 
                                                        $oMTSItem->iIDX, $oMTSItem->ID, $oMTSItem->ID, $oMTSItem->iIDX                        
                                                    ) ;
                        }else{
                            $strData = $strData. sprintf("<input id=\"MTS_%s\" name=\"%s\" class=\"class_none\"  ".
                                                        " type=\"checkbox\" value=\"1\" onclick=\"javascript:enumValues('%s', %d)\" />\n", 
                                                        $oMTSItem->iIDX, $oMTSItem->ID, $oMTSItem->ID, $oMTSItem->iIDX                       
                                                    ) ;
                        } 
                      
                        
                        $strData = $strData. sprintf("<label class=\"choice\" for=\"%s\">%s</label><br/>\n",
                                $oMTSItem->ID,
                                $oMTSItem->getDisplayText()
                                ) ;
                }
            $strData = $strData. "</fieldset></td><td></td></tr>\n" ;
            
            
            $strData = $strData. "<tr><td valign=\"top\"><fieldset style=\"text-align:left\">\n".
                "<legend style=\"padding:7px;text-align:left\">Level 1 Attributes</legend>" ;  
                foreach($this->m_rL1 as $oMTSItem){
                        $strData = $strData. sprintf("<input id=\"MTS_%s\" name=\"%s\" class=\"class_none\" type=\"checkbox\" value=\"1\"".
                                "  onclick=\"javascript:enumValues('%s', %d)\"/>\n", 
                        $oMTSItem->iIDX, $oMTSItem->ID, $oMTSItem->ID, $oMTSItem->iIDX                     
                        ) ;
                        $strData = $strData. sprintf("<label class=\"choice\" for=\"%s\">%s</label><br/>\n",
                                $oMTSItem->ID,
                                $oMTSItem->getDisplayText()
                                ) ;
                        
                }
           
                 
            $strData = $strData. "</fieldset></td>\n" ;
            
            $strData = $strData. "<td valign=\"top\"><fieldset style=\"text-align:left\">\n".
                "<legend style=\"padding:7px;text-align:left\">Level 2 Attributes</legend>" ;  
                foreach($this->m_rL2 as $oMTSItem){
                        $strData = $strData. sprintf("<input id=\"MTS_%s\" name=\"%s\" class=\"class_none\" type=\"checkbox\" value=\"1\"".
                                " onclick=\"javascript:enumValues('%s', %d)\"/>\n", 
                        $oMTSItem->iIDX, $oMTSItem->ID, $oMTSItem->ID, $oMTSItem->iIDX                        
                        ) ;
                        $strData = $strData. sprintf("<label class=\"choice\" for=\"%s\">%s</label><br/>\n",
                                $oMTSItem->ID,
                                $oMTSItem->getDisplayText()
                                ) ;
                }
            //1.Block ENDE    
            //2.Block    
            $strData = $strData. "</fieldset></td></tr>\n" ;
            
            $strData = $strData.  "<tr><td valign=\"top\"><fieldset style=\"text-align:left\">\n".
                "<legend style=\"padding:7px;text-align:left\">Level 3 Attributes</legend>" ;  
                foreach($this->m_rL3 as $oMTSItem){
                        $strData = $strData. sprintf("<input id=\"MTS_%s\" name=\"%s\" class=\"class_none\" type=\"checkbox\" value=\"1\"".
                                " onclick=\"javascript:enumValues('%s', %d)\"/>\n", 
                                $oMTSItem->iIDX, $oMTSItem->ID, $oMTSItem->ID, $oMTSItem->iIDX                        
                        ) ;
                        $strData = $strData. sprintf("<label class=\"choice\" for=\"%s\">%s</label><br/>\n",
                                $oMTSItem->ID,
                                $oMTSItem->getDisplayText()
                                ) ;
                }
            $strData = $strData. "</fieldset></td>\n" ;
            
            $strData = $strData. "<td valign=\"top\"><fieldset style=\"text-align:left\">\n".
                "<legend style=\"padding:7px;text-align:left\">Level 4 Attributes</legend>" ;  
            
                foreach($this->m_rL4 as $oMTSItem){
                        $strData = $strData. sprintf("<input id=\"MTS_%s\" name=\"%s\" class=\"class_none\" type=\"checkbox\" value=\"1\"".
                                " onclick=\"javascript:enumValues('%s', %d)\"/>\n", 
                        $oMTSItem->iIDX, $oMTSItem->ID, $oMTSItem->ID, $oMTSItem->iIDX                        
                        ) ;
                        $strData = $strData. sprintf("<label class=\"choice\" for=\"%s\">%s</label><br/>\n",
                                $oMTSItem->ID,
                                $oMTSItem->getDisplayText()
                                ) ;
                }
            $strData = $strData. "</fieldset></td></tr>\n" ;
            //2.Block ENDE
            //3.Block
          
            $strData = $strData. "</table>\n<!-- END MTS -->\n\n\n\n\n" ;
            $strData = $strData. "\n</html>\n" ;
            return $strData ;
            
        }
            
        public function MTSAttributeDirectory($strLanguage){
            $this->language = $strLanguage ;
            MTSItem::$arrayIDX = 0 ;
            array_push($this->m_rL1, new MTSItem(self::AIRWAY_COMPROMISE, "Airway Compromise",  $this->language , "CHECKBOX"));
            array_push($this->m_rL1, new MTSItem(self::STRIDOR, "Stridor",  $this->language , "CHECKBOX"));
            array_push($this->m_rL1, new MTSItem(self::HYPOGLYCAEMIA, "Hypoglycaemia",  $this->language , "CHECKBOX"));
            array_push($this->m_rL1, new MTSItem(self::EXSANGUATING_HAEMORRHAGE, "Exsanguating Haemorrhage",  $this->language , "CHECKBOX"));
            array_push($this->m_rL1, new MTSItem(self::INADEQUATE_BREATHING, "Inadequate Breathing",  $this->language , "CHECKBOX"));
            array_push($this->m_rL1, new MTSItem(self::CURRENTLY_FITTING, "Currently Fitting",  $this->language , "CHECKBOX"));
            array_push($this->m_rL1, new MTSItem(self::UNRESPONSIVE_CHILD, "Unresponsive Child",  $this->language , "CHECKBOX"));
            array_push($this->m_rL1, new MTSItem(self::SHOCK, "Shock",  $this->language , "CHECKBOX"));                //8 = 37 -> 37 sind  im XLS(Google Docs)
        
            array_push($this->m_rL2, new MTSItem(self::MARKED_TACHYCARDIA,"Marked Tachycardia",  $this->language , "CHECKBOX"));           
            array_push($this->m_rL2, new MTSItem(self::UNCONTROLLABLE_MAJOR_HAEMORRHAGE,"Uncontrollable Major Hamorrhage",  $this->language , "CHECKBOX"));
            array_push($this->m_rL2, new MTSItem(self::UNABLE_TO_TALK_IN_SENTENCES,"Unable to Talk in Sentences",  $this->language , "CHECKBOX"));
            array_push($this->m_rL2, new MTSItem(self::SEVERE_PAIN_OR_ITCH,"Severe Pain or Itch",  $this->language , "CHECKBOX"));
            array_push($this->m_rL2, new MTSItem(self::SIGNIFICANT_MECHANISM_OF_INJURY,"Significant Mechanism of Injury",  $this->language , "CHECKBOX"));
            array_push($this->m_rL2, new MTSItem(self::COLD,"Cold",  $this->language , "CHECKBOX"));            
            array_push($this->m_rL2, new MTSItem(self::ABNORMAL_PULSE,"Abnormal Pulse",  $this->language, "CHECKBOX"));
            array_push($this->m_rL2, new MTSItem(self::ALTERED_CONSCIOUS_LEVEL,"Altered Conscious Level",  $this->language , "CHECKBOX"));
            array_push($this->m_rL2, new MTSItem(self::HOT_CHILD,"Hot Child",  $this->language , "CHECKBOX"));
            array_push($this->m_rL2, new MTSItem(self::VERY_LOW_SAO2,"Very Low SAo2",  $this->language , "CHECKBOX"));
            array_push($this->m_rL2, new MTSItem(self::VERY_HOT_ADULT,"Very Hot Adult",  $this->language , "CHECKBOX"));
            array_push($this->m_rL2, new MTSItem(self::FACIAL_OEDEMA,"Facial Oedema",  $this->language , "CHECKBOX"));   
            array_push($this->m_rL2, new MTSItem(self::OEDEMA_OF_THE_LONGUE,"Oedema of the Longue",  $this->language , "CHECKBOX")); //13 = 29
            
            array_push($this->m_rL3, new MTSItem(self::OPEN_FRACTURE, "Open Fracture", $this->language , "CHECKBOX"));
            array_push($this->m_rL3, new MTSItem(self::HISTORY_OF_UNCONSCIOUSNESS,"History of unconsciousness",  $this->language, "CHECKBOX"));
            array_push($this->m_rL3, new MTSItem(self::HOT_ADULT, "Hot Adult",  $this->language,"Hot Adult", "CHECKBOX"));
            array_push($this->m_rL3, new MTSItem(self::LOW_SAO2, "Low SAO2",  $this->language, "CHECKBOX")); //
            array_push($this->m_rL3, new MTSItem(self::NEW_NEUROLOGICAL_DEFICIT,"New Neurological Deficit",  $this->language , "CHECKBOX"));
            array_push($this->m_rL3, new MTSItem(self::UNSTOPPABLE_MINOR_HAEMORRAGE,"Minor Hamorrhage",  $this->language , "CHECKBOX"));
            array_push($this->m_rL3, new MTSItem(self::WIDESPREAD_RASH,"Widespread Rash",  $this->language , "CHECKBOX"));
            array_push($this->m_rL3, new MTSItem(self::MODERATE_PAIN_OR_ITCH,"Moderate Pain or Itch",  $this->language , "CHECKBOX"));
            array_push($this->m_rL3, new MTSItem(self::SIGNIFICANT_HISTORY_OF_ALLERGY, "Significant History of Allergy",  $this->language , "CHECKBOX"));
            array_push($this->m_rL3, new MTSItem(self::GROSS_DEFORMITY,"Gross Deformity",  $this->language , "CHECKBOX"));                   //10 = 16
            
            array_push($this->m_rL4, new MTSItem(self::WARMTH, "Warmth","" , $this->language, "CHECKBOX")) ;
            array_push($this->m_rL4, new MTSItem(self::RECENT_PROBLEM, "Recent Problem","" , $this->language , "CHECKBOX")) ;
            array_push($this->m_rL4, new MTSItem(self::LOCAL_INFLAMATION, "Local Inflamation", $this->language , "CHECKBOX")) ;
            array_push($this->m_rL4, new MTSItem(self::RECENT_MILD_PAIN_OR_ITCH,  "Recent Mild Pain or Itch" , $this->language , "CHECKBOX"));
            array_push($this->m_rL4, new MTSItem(self::DEFORMITY,"Deformity", $this->language, "CHECKBOX"));          
            array_push($this->m_rL4, new MTSItem(self::SWELLING, "Swelling", $this->language, "CHECKBOX"));  //6
    
            
            array_push($this->m_rCustom, new MTSItem(self::HCC_GENDER, "Gender",  $this->language , "RADIO3"));                      
            array_push($this->m_rCustom, new MTSItem(self::HCC_UNCONSCIOUS,"Unconscious",  $this->language , "CHECKBOX"));                 
            array_push($this->m_rCustom, new MTSItem(self::HCC_DAYTIME, "Daytime",  $this->language , "RADIO3"));                     
            array_push($this->m_rCustom, new MTSItem(self::HCC_RECURRING, "Recurring",  $this->language , "CHECKBOX"));                   
            array_push($this->m_rCustom, new MTSItem(self::HCC_PRIVATE_INSURANCE, "Private Insurance",  $this->language , "CHECKBOX"));           
            array_push($this->m_rCustom, new MTSItem(self::HCC_AGE, "Age", $this->language , "NUMC::3"));         //6 = 43

        }
    }
    
    class MTS extends gen{
        private $m_oD ; //Discriminators
        private $m_oP ; //Pain Values
        private $m_oT ; //Temperature Values
        
        const ON  = 1 ;
        const OFF = 0 ;
        //Custom von hcc ausgedacht:
        const HCC_GENDER                        = "HCC_GENDER" ;
        const HCC_UNCONSCIOUS                   = "HCC_UNCONSCIOUS" ;
        const HCC_DAYTIME                       = "HCC_DAYTIME" ; //morgens, mittags ... 1-4
        const HCC_RECURRING                     = 'HCC_RECURRING' ;
        const HCC_PRIVATE_INSURANCE             = 'HCC_PRIVATE_INSURANCE' ;
        const HCC_AGE                           = 'HCC_AGE' ;        
        const AIRWAY_COMPROMISE                 = "AIRWAY_COMPROMISE";//ab hier Standard MTS
        const INADEQUATE_BREATHING              = "INADEQUATE_BREATHING";
        const EXSANGUATING_HAEMORRHAGE          = "EXSANGUATING_HAEMORRHAGE";
        const UNCONTROLLABLE_MAJOR_HAEMORRHAGE  = "UNCONTROLLABLE_MAJOR_HAEMORRHAGE";  //10     
        
        const SHOCK                             = "SHOCK";
        const CURRENTLY_FITTING                 = "CURRENT_FITTING";
        const UNRESPONSIVE_CHILD                = "UNRESPONSIVE_CHILD";
        const STRIDOR                           = "STRIDOR";
        const HYPOGLYCAEMIA                     = "HYPOGLYCAEMIA";
        const ALTERED_CONSCIOUS_LEVEL           = "ALTERED_CONSCIOUS_LEVEL";
        const HOT_ADULT                         = "HOT_ADULT"; 
        const HOT_CHILD                         = "HOT_CHILD";
        const VERY_HOT_ADULT                    = "VERY_HOT_ADULT";
        const FACIAL_OEDEMA                     = "FACIAL_OEDEMA";//20
        
        const OEDEMA_OF_THE_LONGUE              = "OEDEMA_OF THE_LONGUE";
        const UNABLE_TO_TALK_IN_SENTENCES       = "UNABLE_TO_TALK_IN_SENTENCES";
        const MARKED_TACHYCARDIA                = "MARKED_TACHYCARDIA";        
        const SIGNIFICANT_MECHANISM_OF_INJURY   = "SIGNIFICANT_MECHANISM_OF_INJURY";  
        const ABNORMAL_PULSE                    = "ABNORMAL_PULSE";  
        const LOW_SAO2                          = "LOW_SAO2";
        const WIDESPREAD_RASH                   = "WIDESPREAD_RASH";
        const SIGNIFICANT_HISTORY_OF_ALLERGY    = "SIGNIFICANT_HISTORY_OF_ALLERGY" ;
        const HISTORY_OF_ALLERGY                = "HISTORY_OF_ALLERGY"; 
//        const FRACTURE                          = "FRACTURE";   //OPEN         
        const HISTORY_OF_UNCONSCIOUSNESS        = "HISTORY_OF_UNCONSCIOUSNESS" ;//30
        
        const LOCAL_INFLAMATION                 = "LOCAL_INFLAMATION";                   
        const RECENT_PROBLEM                    = "RECENT_PROBLEM" ; //Beschwerden bestehen schon länger //40
        const RECENT_MILD_PAIN_OR_ITCH          = "RECENT_MILD_PAIN_OR_ITCH"; //on/off  
        const SEVERE_PAIN_OR_ITCH               = "SEVERE_PAIN_OR_ITCH"; //on/off  
        const VERY_LOW_SAO2                     = "VERY_LOW_SAO2";
        const DEFORMITY                         = "DEFORMITY";        
        const GROSS_DEFORMITY                   = "GROSS_DEFORMITY";  
        const OPEN_FRACTURE                     = "OPEN_FRACTURE";  
        const SWELLING                          = "SWELLING"; 
        const MODERATE_PAIN_OR_ITCH             = "MODERATE_PAIN_OR_ITCH";  //40
        
        const WARMTH                            = "WARMTH";  
        const COLD                              = "COLD"; 
        const UNSTOPPABLE_MINOR_HAEMORRAGE      = "UNSTOPPABLE_MINOR_HAEMORRAGE";
        const NEW_NEUROLOGICAL_DEFICIT          = "NEW_NEUROLOGICAL_DEFICIT" ;  //44
        
        
        
        private $m_rL1 = array() ; //alle kritischen Merkmale
        private $m_rL2 = array() ; //Merkmale, für Level 2
        private $m_rL3 = array() ; //Merkmale, für Level 3
        private $m_rL4 = array() ; //Merkmale, für Level 4
        private $m_rL5 = array() ; //Merkmale, für Level 5
        private $m_rCustom = array( ) ; //hcc::Merkmale
        function __construct($DEBUG) {
            //$this->init() ;      
            $this->m_DEBUG = $DEBUG ;
            
   
            
                    
            array_push($this->m_rL4, self::WARMTH) ;            
            array_push($this->m_rL4, self::RECENT_PROBLEM) ;
            array_push($this->m_rL4, self::LOCAL_INFLAMATION) ;
            array_push($this->m_rL4, self::RECENT_MILD_PAIN_OR_ITCH) ;
            array_push($this->m_rL4, self::DEFORMITY) ;          
            array_push($this->m_rL4, self::SWELLING) ;  //6
            
            
            
            array_push($this->m_rL3, self::OPEN_FRACTURE) ;
            array_push($this->m_rL3, self::HISTORY_OF_UNCONSCIOUSNESS) ;
            array_push($this->m_rL3, self::HOT_ADULT) ;
            array_push($this->m_rL3, self::LOW_SAO2) ; //
            array_push($this->m_rL3, self::NEW_NEUROLOGICAL_DEFICIT) ;
            array_push($this->m_rL3, self::UNSTOPPABLE_MINOR_HAEMORRAGE) ;

            array_push($this->m_rL3, self::WIDESPREAD_RASH);
            array_push($this->m_rL3, self::MODERATE_PAIN_OR_ITCH) ;
            array_push($this->m_rL3, self::SIGNIFICANT_HISTORY_OF_ALLERGY) ;
            array_push($this->m_rL3, self::GROSS_DEFORMITY) ;                   //10 = 16
        
        //    print_r($this->m_rL3) ;
            
            array_push($this->m_rL2, self::MARKED_TACHYCARDIA) ;           
            array_push($this->m_rL2, self::UNCONTROLLABLE_MAJOR_HAEMORRHAGE) ;
            array_push($this->m_rL2, self::UNABLE_TO_TALK_IN_SENTENCES) ;
            array_push($this->m_rL2, self::SEVERE_PAIN_OR_ITCH) ;
            array_push($this->m_rL2, self::SIGNIFICANT_MECHANISM_OF_INJURY) ;
            array_push($this->m_rL2, self::COLD) ;            
            array_push($this->m_rL2, self::ABNORMAL_PULSE) ;
            array_push($this->m_rL2, self::ALTERED_CONSCIOUS_LEVEL) ;
            array_push($this->m_rL2, self::HOT_CHILD) ;
            array_push($this->m_rL2, self::VERY_LOW_SAO2) ;
            array_push($this->m_rL2, self::VERY_HOT_ADULT) ;
            array_push($this->m_rL2, self::FACIAL_OEDEMA) ;   
            array_push($this->m_rL2, self::OEDEMA_OF_THE_LONGUE) ; //13 = 29
            
            
            array_push($this->m_rL1, self::AIRWAY_COMPROMISE) ;
            array_push($this->m_rL1, self::STRIDOR) ;
            array_push($this->m_rL1, self::HYPOGLYCAEMIA) ;
            array_push($this->m_rL1, self::EXSANGUATING_HAEMORRHAGE) ;
            array_push($this->m_rL1, self::INADEQUATE_BREATHING) ;
            array_push($this->m_rL1, self::CURRENTLY_FITTING) ;
            array_push($this->m_rL1, self::UNRESPONSIVE_CHILD) ;
            array_push($this->m_rL1, self::SHOCK) ;                //8 = 37 -> 37 sind  im XLS(Google Docs)
            
            array_push($this->m_rCustom, self::HCC_GENDER);                      
            array_push($this->m_rCustom, self::HCC_UNCONSCIOUS);                 
            array_push($this->m_rCustom, self::HCC_DAYTIME);                     
            array_push($this->m_rCustom, self::HCC_RECURRING);                   
            array_push($this->m_rCustom, self::HCC_PRIVATE_INSURANCE);           
            array_push($this->m_rCustom, self::HCC_AGE);         //6 = 43
            printf("The Vectors has <b>[%d]</b> Elements.<br>", sizeof($this->m_rL1) +
                        sizeof($this->m_rL2) +
                        sizeof($this->m_rL3) +
                        sizeof($this->m_rL4) +
                        sizeof($this->m_rCustom)
                        ) ;

            //erst mal nicht: array_push($this->m_rL1, self::TEMPERATURE) ;
        }
        
        public function asHTMLDlg(){
            $oDir = new MTSAttributeDirectory('DE') ;
            return $oDir->asHtml() ;
        }
        
        private function init(){            
            $this->m_oT = new MTS_TEMPERATURE_VALUES() ;
            $this->m_oP = new MTS_PAIN_VALUES() ;
        }
        
        private function make_seed(){
          list($usec, $sec) = explode(' ', microtime());
          return $sec + $usec * 1000000;
        }
        
        private function random( $iLow, $iHigh ){
            //$this->make_seed( ) ;
            return rand( $iLow, $iHigh ) ;
        }
        
        /*
         * 1.9.2.21
         * Clear a level in one row
         */
        private function clearLevel($rKeys, &$rTargetArray){
            foreach($rKeys as $key){
                    $rTargetArray[$key] = self::OFF ;
                    //printf("CLEARED %s VALUE IS NOW: %d<br>", $key, $rTargetArray[$key]) ;
            }
        }
        //set all elements to ON in a level
        private function setLevel($rKeys, &$rTargetArray){
                foreach($rKeys as $key){
                    $rTargetArray[$key] = self::ON ;
            }
        }
        
        /*
         * Dinge, die sich gegenseitig ausschliessen:
         * HOT_CHILD <-> VERY_HOT_ADULT
         * UNABLE_TO_TALK_IN_SENTENCES <-> UNCONSCIOUS
         * ++?
         */
        private function apply($rRow){
            if( self::ON == $rRow[self::UNCONSCIOUS] && 
                    ( self::ON == $rRow[self::UNRESPONSIVE_CHILD] || 
                      self::ON == $rRow[self::ALTERED_CONSCIOUS_LEVEL] ||
                      self::ON == $rRow[self::UNABLE_TO_TALK_IN_SENTENCES] 
                    )
               ){
                    $rRow[self::UNCONSCIOUS] = self::OFF ;
                }
                
            if( self::ON == $rRow[self::HOT_CHILD] || self::ON == $rRow[self::HOT_ADULT] 
                    || self::ON == $rRow[self::VERY_HOT_ADULT]){
                        $rRow[self::WARMTH] = self::OFF ;
                        $rRow[self::COLD] = self::OFF ;
            } 
            
            if( self::ON == $rRow[self::EXSANGUATING_HAEMORRHAGE] && 
                    ( 
                        self::ON == $rRow[self::UNSTOPPABLE_MINOR_HAEMORRAGE]  
                    )
                    
               ){
                    $rRow[self::UNSTOPPABLE_MINOR_HAEMORRAGE] = self::OFF ;
                }  
                
                
            if( self::ON == $rRow[self::HOT_CHILD] && 
                    ( 
                        self::ON == $rRow[self::VERY_HOT_ADULT]  
                    )
                    
               ){
                    $rRow[self::VERY_HOT_ADULT] = self::OFF ;
                }    
                
            if( self::ON == $rRow[self::HOT_CHILD] && 
                    ( 
                        $rRow[self::HCC_AGE] > 13
                    )
               ){
                    $rRow[self::HOT_CHILD] = self::OFF ;
                    $rRow[self::HOT_ADULT] = self::ON ;
                }     
                
            if( self::ON == $rRow[self::DEFORMITY] && 
                    ( 
                        self::ON == $rRow[self::GROSS_DEFORMITY] 
                    )
               ){
                    $rRow[self::DEFORMITY] = self::OFF ;
                }     
                
            //PAIN und MILD PAIN schliessen sich aus.
            if( self::ON == $rRow[self::RECENT_MILD_PAIN_OR_ITCH] && 
                    ( 
                        self::ON ==  $rRow[self::SEVERE_PAIN_OR_ITCH]   
                    )
               ){
                    $rRow[self::RECENT_MILD_PAIN_OR_ITCH] = self::OFF ;
                } 
            //warm und kalt geht nicht zusammen    
            if( self::ON == $rRow[self::WARMTH] && 
                    ( 
                        self::ON == $rRow[self::COLD]  
                    )
               ){
                    $rRow[self::COLD] = self::OFF ;
                } 
                
            if( self::ON == $rRow[self::RECENT_MILD_PAIN_OR_ITCH] && 
                    ( 
                        $rRow[self::PAIN] > 1  
                    )
               ){
                    $rRow[self::RECENT_MILD_PAIN_OR_ITCH] = self::OFF ;
                }         
                
        }
        
        public function genRandom(&$rValues){
            $rRow = array() ;
            $iIdx =  0 ;
            $this->clearLevel($this->m_rL1, $rRow) ;  //lösche alle höheren level werte raus.
            $this->clearLevel($this->m_rL2, $rRow) ;
            $this->clearLevel($this->m_rL3, $rRow) ;
            $this->clearLevel($this->m_rL4, $rRow) ;
            //level 5 sind alle, die nur den wert OFF haben
            
            //
            
            
                $rRow[self::HCC_GENDER]                         = $this->random(1, 3)  ; //0,1,2
                $rRow[self::HCC_UNCONSCIOUS]                    = $this->random(0, self::ON) ;            
                $rRow[self::HCC_PRIVATE_INSURANCE]              = $this->random(1, self::ON) ;
                $rRow[self::HCC_AGE              ]              = $this->random(1, 110) ;
                $rRow[self::HCC_RECURRING              ]        = $this->random(0, self::ON) ;
                $rRow[self::HCC_DAYTIME    ]                    = $this->random(1, 4) ;
            
                $iIdx = mt_rand(0, count($rValues) - 1); //so viele Merkmale machen das aus, eines nehmen wir.
                $rRow[$rValues[$iIdx]] = self::ON ;
            
            
            /*
            foreach($this->m_rL1 as $key){               
                $rRow[$key] = $this->random(1, 2);
                $rRow[self::HCC_GENDER]                         = $this->random(1, 2)  ;
                $rRow[self::HCC_UNCONSCIOUS]                    = $this->random(1, 2) ;            
                $rRow[self::HCC_PRIVATE_INSURANCE]              = $this->random(1, 2) ;
                $rRow[self::HCC_AGE              ]              = $this->random(1, 110) ;
                $rRow[self::HCC_RECURRING              ]        = $this->random(1, 2) ;
                $rRow[self::HCC_DAYTIME    ]                    = $this->random(1, 4) ;
            }
            foreach($this->m_rL2 as $key){               
                $rRow[$key] = $this->random(1, 2);
                $rRow[self::HCC_GENDER]                         = $this->random(1, 2)  ;
                $rRow[self::HCC_UNCONSCIOUS]                    = $this->random(1, 2) ;            
                $rRow[self::HCC_PRIVATE_INSURANCE]              = $this->random(1, 2) ;
                $rRow[self::HCC_AGE              ]              = $this->random(1, 110) ;
                $rRow[self::HCC_RECURRING              ]        = $this->random(1, 2) ;
                $rRow[self::HCC_DAYTIME    ]                    = $this->random(1, 4) ;
            }
            foreach($this->m_rL3 as $key){                
                $rRow[$key] = $this->random(1, 2);
                $rRow[self::HCC_GENDER]                         = $this->random(1, 2)  ;
                $rRow[self::HCC_UNCONSCIOUS]                    = $this->random(1, 2) ;            
                $rRow[self::HCC_PRIVATE_INSURANCE]              = $this->random(1, 2) ;
                $rRow[self::HCC_AGE              ]              = $this->random(1, 110) ;
                $rRow[self::HCC_RECURRING              ]        = $this->random(1, 2) ;
                $rRow[self::HCC_DAYTIME    ]                    = $this->random(1, 4) ;
            }
            foreach($this->m_rL4 as $key){                
                $rRow[$key] = $this->random(1, 2);
                $rRow[self::HCC_GENDER]                         = $this->random(1, 2)  ;
                $rRow[self::HCC_UNCONSCIOUS]                    = $this->random(1, 2) ;            
                $rRow[self::HCC_PRIVATE_INSURANCE]              = $this->random(1, 2) ;
                $rRow[self::HCC_AGE              ]              = $this->random(1, 110) ;
                $rRow[self::HCC_RECURRING              ]        = $this->random(1, 2) ;
                $rRow[self::HCC_DAYTIME    ]                    = $this->random(1, 4) ;
            }     
            */
            return $rRow ;
        }
        
        
        /* CLASS MTS
         * Normal generieren, dann aber bestimmte Werte prüfen und ggf. hochsetzen
         */
        public function genUrgent($nRows){            
            $iIdx = 0 ; //zum Würfeln.
            if($this->m_DEBUG){
                printf("<br>%s:%d Generating [%d] rows.<br>", __METHOD__, __LINE__, $nRows) ;
            }
            for($i=0;$i<$nRows;$i++){
                $rRow = $this->genRandom($this->m_rL1) ; //zunächst wird ein beliebiger Datensatz generiert, dann aufgepimpt:
                //wenigstens eins von diesen muss dann gesetzt sein:
                //kein Urgent generiert                
            /*
                if( false == $this->isUrgent($rRow) ){
                    $iIdx = mt_rand(0, count($this->m_rL1) - 1); //so viele Merkmale machen Urgent aus
                    $rRow[$this->m_rL1[$iIdx]] = self::ON ;
                }
            */    
                //aber auch: einige Merkmale schliessen sich gegenseitig aus:
                //HOT_CHILD / HOT_ADULT
                //Wenn Alter > 13, dann kann HOT_CHILD nicht sein
                //Wenn bewusstlos, dann kann Talking... nicht sein
                if( self::ON == $rRow[self::HOT_CHILD] &&  self::ON == $rRow[self::VERY_HOT_ADULT] ){
                   if( $rRow[self::HCC_AGE] >= 16 ){ //adult
                       $rRow[self::HOT_CHILD]      = self::OFF ; //zurücksetzen
                   }else{
                       $rRow[self::VERY_HOT_ADULT] = self::OFF ;
                   }
                }
                if( self::ON == $rRow[self::HOT_CHILD] &&  $rRow[self::HCC_AGE] >= 13 ){
                    $rRow[self::VERY_HOT_ADULT] = self::ON ;
                    $rRow[self::HOT_CHILD] = self::OFF ;
                }
                //bewusstlos und unable to talk in sentences passt nicht zusammen.
                if( self::ON == $rRow[self::HCC_UNCONSCIOUS] && self::ON == $rRow[self::UNABLE_TO_TALK_IN_SENTENCES] ){
                    $rRow[self::UNABLE_TO_TALK_IN_SENTENCES] = self::OFF ;
                }
                array_push($this->m_rData, $rRow) ;
            }            
            
        }
        /*CLASS MTS
         * 5/2019 Generieren eines Batches mit allen Altern und möglichst vielen mehrfach Attributen f. urgent
         * Erweiterung: level als parameter: -> alle Level nach dem gesetzten auf ON
         */
        public function genTraining($rWhat, $nRows, $iLevel){
            $iCount = 0 ;
            printf("%s Count is %d<br>\n", __MEHTOD__, $iCount) ;
            $iNumElementsSet = 0 ;
            $iIdx = 0 ;
            for($i=0;$i<$nRows;$i++){
                $iCount = mt_rand(1, sizeof($rWhat)) ;
                printf("%s ------>   generating row %d<br>\n", __MEHTOD__, $i) ;
                $rRow = $this->genRandom($rWhat) ; //zunächst wird ein beliebiger Datensatz generiert, dann aufgepimpt: 
                for($k=0;$k<$iCount; $k++){       
                        $iIdx = mt_rand(1, sizeof($rWhat)) ; //das kann evt. mehrfach den gleichen Index treffen - macht erstmal nichts.
                        //printf("Putting a 1 at %s<br>\n", $rRow[$iIdx]) ;
                        $rRow[$rWhat[$iIdx]] = self::ON ;                        
                }
                
                switch($iLevel){
                    case 1:
                        $this->setLevel($this->m_rL2, $rRow) ;
                        $this->setLevel($this->m_rL3, $rRow) ;
                        $this->setLevel($this->m_rL4, $rRow) ;
                        break ;
                    case 2:
                        $this->setLevel($this->m_rL3, $rRow) ;
                        $this->setLevel($this->m_rL4, $rRow) ;
                        break ;
                    case 3:
                        $this->setLevel($this->m_rL4, $rRow) ;
                        break ;
                    default:
                        break ;
                     
                }
                
                print_r($rRow) ;
                array_push($this->m_rData, $rRow) ;
                
                $iNumElementsSet = 0 ;
            }     
        }
        
        //CLASS MTS
        public function genTrainingExtra1($nRows){
            $this->genTraining($this->m_rL1, $nRows, 1) ;
            //alle anderen werden gesetzt, um lernen zu können, dass Level 1 immer Vorrang hat.
        /*
            $this->setLevel($this->m_rL1) ;
            $this->setLevel($this->m_rL2) ;
            $this->setLevel($this->m_rL3) ;
            $this->setLevel($this->m_rL4) ;
        */    
        }
                                                
        //CLASS MTS
        public function genTrainingExtra2($nRows){
            $this->genTraining($this->m_rL2, $nRows, 2) ;
        }
        //CLASS MTS
        public function genTrainingExtra3($nRows){
            $this->genTraining($this->m_rL3, $nRows, 3) ;
        }
        //CLASS MTS
        public function genTrainingExtra4($nRows){
            $this->genTraining($this->m_rL4, $nRows, 4) ;
        }
        
        /* Level 4
         * Normal generieren, dann aber bestimmte Werte prüfen und ggf. hochsetzen
         */
        public function genLevel4($nRows){            
            $iIdx = 0 ; //zum Würfeln.
            $i = 0 ;
            $k = 0 ;
            if($this->m_DEBUG){
                printf("<br>%s:%d Generating [%d] rows.<br>", __METHOD__, __LINE__, $nRows) ;
            }
        
            for($i=0;$i<$nRows;$i++){
                $rRow = $this->genRandom($this->m_rL4) ; //zunächst wird ein Urgent Datensatz generiert, dann abgepimpt:
                
                /*
                print("<p style=\"color:white;background-color:green\">Cleared L1: </p><br>") ;
                print_r($rRow) ;
                print("Cleared L1: <br>") ;
                */
                /*
                $iIdx = mt_rand(0, count($this->m_rL4) - 1); //so viele Merkmale machen das aus, eines nehmen wir.
                if($this->m_DEBUG){
                    //
                    //printf("<br>%s:%s Setting [%s] to ON.<br>", __METHOD__, __LINE__, $this->m_rL4[$iIdx]) ;
                }
                $rRow[$this->m_rL4[$iIdx]] = self::ON ;
                */
                array_push($this->m_rData, $rRow) ;
            }            
            return $rRow ;
        }
        
        /* Level 3
         * Normal generieren, dann aber bestimmte Werte prüfen und ggf. hochsetzen
         */
        public function genLevel3($nRows){            
            $iIdx = 0 ; //zum Würfeln.
            $i = 0 ;
            $k = 0 ;
            if($this->m_DEBUG){
                printf("<br>%s:%d Generating [%d] rows.<br>", __METHOD__, __LINE__, $nRows) ;
            }
        
            for($i=0;$i<$nRows;$i++){
                $rRow = $this->genRandom($this->m_rL3) ; //zunächst wird ein Urgent Datensatz generiert, dann abgepimpt:
                //paar custom sachen einschränken.
                $rRow[self::HCC_UNCONSCIOUS]                    = self::OFF ;               
                array_push($this->m_rData, $rRow) ;
            }
            return $rRow ;
        }
        
        /* Level 3
         * Normal generieren, dann aber bestimmte Werte prüfen und ggf. hochsetzen
         */
        public function genLevel2($nRows){
            $iIdx = 0 ; //zum Würfeln.
            $i = 0 ;
            $k = 0 ;
            if($this->m_DEBUG){
                printf("<br>%s:%d Generating [%d] rows.<br>", __METHOD__, __LINE__, $nRows) ;
            }
            for($i=0;$i<$nRows;$i++){
                $rRow = $this->genRandom($this->m_rL2) ; //zunächst wird ein Urgent Datensatz generiert, dann abgepimpt:
                array_push($this->m_rData, $rRow) ;
            }            
            return $rRow ;
        }
        
        
        /* CLASS MTS Average Joe kann alles sein, wird aber hier so für Level 3 oder 4 generiert.
         * AvJoe sollte ein Level 3 oder 4 erzeugen
         * 1.0 2.0 2.0 1.0 1.0 2.0 1.0 1.0 1.0 2.0 
         * 2.0 3.0 2.0 2.0 1.0 2.0 2.0 1.0 2.0 2.0 
         * 1.0 1.0 2.0 2.0 2.0 2.0 2.0 1.0 1.0 2.0 
         * 2.0 1.0 2.0 
         * Ergebnis 5 Einträge 
         * 0.0 1.0 0.0 0.0 0.0 
         */
        public function genAverageJoe($nRows){ //sollte Level 3 oder Level 4 rauskommen
            
         return ; //nicht genutzt z.Z.   
            
            if($this->m_DEBUG){
                printf("<br>%s:%d Generating [%d] rows.<br>", __METHOD__, __LINE__, $nRows) ;
            }
            
            for($i=0;$i<$nRows;$i++){
                $rRow = $this->genRandom( ) ; //zunächst wird ein beliebiger Datensatz generiert, dann aufgepimpt:                
                //paar custom sachen einschränken.
                $rRow[self::HCC_UNCONSCIOUS]                    = self::OFF ;            
                $rRow[self::HCC_RECURRING              ]        = self::OFF ;         
                $rRow[self::WIDESPREAD_RASH]                    = $this->random(1, 2) ;
                $rRow[self::HISTORY_OF_ALLERGY]                 = $this->random(1, 2) ;
                $rRow[self::NEW_NEUROLOGICAL_DEFICIT]           = $this->random(1, 2) ;
                $rRow[self::FRACTURE]                           = $this->random(1, 2) ;
                $rRow[self::HISTORY_OF_UNCONSCIOUSNESS]         = $this->random(1, 2) ;
                $rRow[self::LOCAL_INFLAMATION]                  = $this->random(1, 2) ;
                $rRow[self::RECENT_PROBLEM]                     = $this->random(1, 2) ;
                $rRow[self::DEFORMITY]                          = self::OFF ;
                $rRow[self::SWELLING]                           = $this->random(1, 2) ; 
                array_push($this->m_rData, $rRow) ;
            }   
        }
        
        /*
         * CLASS MTS
         */
        public function genHypochondriac($nRows){ //level 4 oder level 5
            if($this->m_DEBUG){
                printf("<br>%s:%d Generating [%d] rows.<br>", __METHOD__, __LINE__, $nRows) ;
            }
            //1.9.4.30
            
            
            for($i=0;$i<$nRows;$i++){
                //paar custom sachen einschränken.
                $rRow[self::HCC_UNCONSCIOUS]                    = self::OFF ;            
                $rRow[self::HCC_AGE              ]              = $this->random(1, 80) ;
                $rRow[self::HCC_RECURRING              ]        = self::ON ;
                $rRow[self::HCC_DAYTIME    ]                    = $this->random(2, 3) ; //nur mittag abends                
                $rRow[self::HCC_GENDER]                         = $this->random(1, 3)  ;
                $rRow[self::HCC_PRIVATE_INSURANCE]              = $this->random(1, self::ON) ;
                
                
                //einfach nichts
                $this->clearLevel($this->m_rL1, $rRow) ;
                $this->clearLevel($this->m_rL2, $rRow) ;
                $this->clearLevel($this->m_rL3, $rRow) ;
                $this->clearLevel($this->m_rL4, $rRow) ;
                array_push($this->m_rData, $rRow) ;
            }
        }
        
        /* CLASS MTS
         * Muss angepasst werden
         */
        public function isUrgent($rRow){
            if($this->m_DEBUG){                
                for($i=0;$i<sizeof($this->m_rL1);$i++){
                    printf("[%s] = [%d]<br>", $this->m_rL1[$i], $rRow[$this->m_rL1[$i]]) ;
                }
            }
            $bRet = false ;
              for($k=0; $k<sizeof($this->m_rL1); $k++){                    
                    if( self::ON == $rRow[$this->m_rL1[$k]] ){
                        $bRet = true ;
                        if( $this->m_DEBUG ){
                            printf("%s - %s was found true.<br>", __METHOD__, $this->m_rL1[$k]) ;
                        }
                        break ;
                    } 
                }  
           
            if($this->m_DEBUG){
                printf("[%s] Ergebnis is [%s]<br>", __METHOD__, $bRet==true?"URGENT":"NICHT URGENT") ;
            }
            return $bRet ;
        } 
//CLASS MTS        
        /*
         * no serious symptoms 
         * Level 5
         */
        public function isHypochondriac($rRow){
              return ( $rRow[self::HCC_UNCONSCIOUS] <= self::ON || 
                      $rRow[self::AIRWAY_COMPROMISE] <= self::ON || 
                      $rRow[self::EXSANGUATING_HAEMORRHAGE] <= self::ON || 
                      $rRow[self::VERY_HOT_ADULT] <= self::ON ||
                      $rRow[self::HYPOGLYCAEMIA] <= self::ON ||
                      $rRow[self::UNRESPONSIVE_CHILD] <= self::ON || 
                      $rRow[self::SHOCK] <= self::ON || 
                      $rRow[self::SIGNIFICANT_MECHANISM_OF_INJURY] <= self::ON ||
                      $rRow[self::HOT_CHILD] < self::ON) ;        
        }
        
        /*
         * serious symptoms 
         * Level 2
         * Hot child
            Very hot adult
            Facial oedema
            Oedema of the longue
            Unable to talk in sentences
            Marked tachycardia
            Severe Pain or itch
            Significant mechanism of injury
            Abnormal pulse
            Cold
            Very low SaO2
         */
        public function isLevel2($rRow){
              if( true == $this->isUrgent($row) ){
                  return false ;
              }
            
            $bRet = false ;
              for($k=0; $k<sizeof($this->m_rL2); $k++){                    
                    if( self::ON == $rRow[$this->m_rL2[$k]] ){
                        $bRet = true ;
                        if( $this->m_DEBUG ){
                            printf("%s %d - %s was found true.<br>", __METHOD__, __LINE__, $this->m_rL2[$k]) ;
                        }
                        break ;
                    } 
                }  
            return $bRet ;    
        }
        /*
         * nicht bewusslos, oder anderweitig schwere Erkrankung.
         */
        public function isLevel3($rRow){
            $bRet = false ;
            for($k=0; $k<sizeof($this->m_rL3); $k++){
                if( $this->m_DEBUG ){
                    printf("%s - Checking %s value %s.<br>", __METHOD__, $this->m_rL3[$k], $rRow[$this->m_rL3[$k]]) ;
                }                  
                if( self::ON == $rRow[$this->m_rL3[$k]] ){
                    $bRet = true ;
                    if( $this->m_DEBUG ){
                        printf("%s - %s was found true.<br>", __METHOD__, $this->m_rL3[$k]) ;
                    }
                    break ;
                } 
            }  
            return $bRet ;
        }
        /* CLASS MTS
         * Keine der relevanten Merkmale vorhanden
         */
        public function isLevel4($rRow){             
              $bRet = false ;
              for($k=0; $k<sizeof($this->m_rL4); $k++){         
                  //printf("%s - %s was found.<br>", __METHOD__, $this->m_rL4[$k]) ;
                    if( self::ON == $rRow[$this->m_rL4[$k]] ){
                        $bRet = true ;
                        if( $this->m_DEBUG ){
                            printf("%s - %s was found [%d].<br>", __METHOD__, $this->m_rL4[$k], $rRow[$this->m_rL4[$k]]) ;                            
                        }
                        break ;
                    } 
                }  
            return $bRet ;
        }
        /*
         * Keine der relevanten Merkmale vorhanden,
         * und Wiederkehrer.
         * Brauchen wir eigentlich nicht - 1-4 reicht
         */
        public function isLevel5($rRow){
            return true ; 
            
        }
        
        //nur f. Eval
        public function isAverageJoe($rRow){
            
                
        }
//CLASS MTS        
        public function generateBatch($nRows, $iType){
            $iBewusstlos = 0 ;
            $iSchmerz    = 0 ;
            $iTemp       = 0 ;
            $iBlut       = 0 ;
            $iAtmung     = 0 ;
            
            for($i = 0; $i < $nRows;$i++){
                switch( $iType ){
                    case( __HYPOCHONDRIAC__ ):
                        return $this->genHypoChondriac($nRows) ;
                        break ;
                    case( __AVERAGE_JOE__ ):
                        return $this->genAverageJoe($nRows) ;
                        break ;
                    case( __LEVEL2__ ): //so ungefähr level 3 und 4
                        return $this->genLevel2($nRows) ;
                        break ;
                    case( __LEVEL3__ ): //so ungefähr level 3 und 4
                        return $this->genLevel3($nRows) ;
                        break ;
                    case( __LEVEL4__ ): //so ungefähr level 3 und 4
                        return $this->genLevel4($nRows) ;
                        break ;
                    case( __URGENT__ ):  //Level 1
                    case( __LEVEL1__ ):
                        return $this->genUrgent($nRows) ;
                        break ; 
                    default:
                        return $this->genRandom($nRows) ;
                }
            }
        }
        /* 
         * CLASS MTS
         */
        public function classify(){            
            foreach($this->m_rData as &$this->m_rRow){                
                $this->m_rRow['EINSTUFUNG'] = 5 ;  //Default
                if( $this->m_DEBUG ){
                    printf("<hr>%s:%d<br>", __METHOD__, __LINE__) ;
                    $rElements = array_keys($this->m_rRow) ;
                    for($i=0;$i<sizeof($rElements);$i++){
                        printf("[%s] = [%d]<br>", $rElements[$i], $this->m_rRow[$rElements[$i]]) ;
                    }
                    print("<br>") ;
                }
                
                
                //printf("<br>Checking for Urgent ZEILE [%d]<br>", __LINE__) ;
                if($this->isUrgent($this->m_rRow)){   //Level1                 
                    $this->m_rRow['EINSTUFUNG'] = 1 ;
                    if( $this->m_DEBUG ){
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "LEVEL_1", __LINE__) ;
                    }
                    continue ;
                }
                else{
                    if( $this->m_DEBUG ){
                        print("n i c h t  U R G E N T<br>") ;
                    } 
                }
            
                /*
                if( $this->m_DEBUG ){
                    printf("<b style=\"color:white;background-color:blue\">EINGESTUFT als [%d]</b><br>", $this->m_rRow['EINSTUFUNG']) ;
                    print("<hr>") ;
                } 
                */
                
                if($this->isLevel2($this->m_rRow)){                    
                    $this->m_rRow['EINSTUFUNG'] = 2 ;
                    if( $this->m_DEBUG ){
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "LEVEL_2", __LINE__) ;
                    }
                    continue ;
                }  
           
                if($this->isLevel3($this->m_rRow)){                    
                    $this->m_rRow['EINSTUFUNG'] = 3 ;
                    if( $this->m_DEBUG ){
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "LEVEL_3", __LINE__) ;
                    }
                    continue ;
                }
                
                /*
                //wir brauchen die 3, 4 und 5 Kategorie
                if($this->isAverageJoe($this->m_rRow)){                    
                    $this->m_rRow['EINSTUFUNG'] = 3 ;
                    if( $this->m_DEBUG ){                        
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "__AVERAGE_JOE__", __LINE__) ;
                    }  
                    continue ;
                }                
                */
                
               
                if( $this->isLevel4($this->m_rRow) ){   //                        
                    $this->m_rRow['EINSTUFUNG'] = 4 ;
                    if( $this->m_DEBUG ){
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "LEVEL_4", __LINE__) ;
                    }                            
                    continue ;
                }
            
                if($this->isHypochondriac($this->m_rRow)){                    
                    $this->m_rRow['EINSTUFUNG'] = 5 ;
                    if( $this->m_DEBUG ){
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "HYPOCHONDER", __LINE__) ;
                    }                     
                }
                if( $this->m_rRow['EINSTUFUNG'] == 5 ){
                    print("<br>") ;
                    print_r($this->m_rRow) ;
                }
            }//foreach
            
        }
        
        
        
        /* CLASS MTS
         * es wird auf 24 spalten aufgefüllt
         */
        public function toFile($strName, $cDelimiter, $DEBUG=null){
            $strDataFilename   = sprintf("%s_data.csv", $strName) ;
            //$strResultFilename = sprintf("%s_result.csv", $strName) ;
            $strResult         = null ;
            $strData           = null ;
            unlink ( $strDataFilename ) ;
            //unlink ( $strResultFilename ) ;
            print("<br>") ;
            
            if($DEBUG){
                printf("<hr>%s %s The Vectors have <b>[%d]</b> Elements. L1 %d + L2 %d + L3 %d + L4 %d + CUSTOM %d<hr>", 
                        __METHOD__, __LINE__,
                        sizeof($this->m_rL1) +
                        sizeof($this->m_rL2) +
                        sizeof($this->m_rL3) +
                        sizeof($this->m_rL4) +
                        sizeof($this->m_rCustom),
                        sizeof($this->m_rL1) ,
                        sizeof($this->m_rL2) ,
                        sizeof($this->m_rL3) ,
                        sizeof($this->m_rL4) ,
                        sizeof($this->m_rCustom)
                        ) ;
                $strData = null ;
                /*
                foreach($this->m_rL1 as $key){
                        $strData = sprintf("%s%s%s", $strData, $strData!=null?',':'', $this->m_rRow[$key]) ;
                }
                foreach($this->m_rL2 as $key){
                        $strData = sprintf("%s%s%s", $strData, $strData!=null?',':'', $this->m_rRow[$key]) ;
                }
                foreach($this->m_rL3 as $key){
                        $strData = sprintf("%s%s%s", $strData, $strData!=null?',':'', $this->m_rRow[$key]) ;
                }
                foreach($this->m_rL4 as $key){
                        $strData = sprintf("%s%s%s", $strData, $strData!=null?',':'', $this->m_rRow[$key]) ;
                }  
                */
                foreach($this->m_rCustom as $key){
                        $strData = sprintf("%s%s%s", $strData, $strData!=null?',':'', $this->m_rRow[$key]) ;
                }
                printf("%s %s <br>[%s]<br>", __METHOD__, __LINE__, $strData) ;
                
                
                /*
                $rElements = array_keys($this->m_rRow) ;
                for($i=0;$i<sizeof($rElements);$i++){
                    printf("[%s]<br>", $rElements[$i]) ;
                } */               
            }
            
            foreach($this->m_rData as $this->m_rRow){ //fünf Einstufungen
                switch($this->m_rRow['EINSTUFUNG']){
                 case 1:
                     $strResult = "1,0,0,0,0" ; //die Buchstaben sind nur Hilfe f. Lesen des Outputs
                     break ;
                 case 2:
                     $strResult = "0,1,0,0,0" ;
                     break ;
                 case 3:
                     $strResult = "0,0,1,0,0" ;
                     break ;
                 case 4:
                     $strResult = "0,0,0,1,0" ;                     
                     break ;
                 case 5:
                     $strResult = "0,0,0,0,1" ;                     
                     break ;
                 default:       
                     $strResult = "0,0,0,0,1" ;                     
                }
                
            $strData = null ;
            //die arrays werden abgeloop und alle Werte erfasst, u. an strData angehängt.
            foreach($this->m_rL1 as $key){
                    $strData = sprintf("%s%s%s", $strData, $strData!=null?',':'', $this->m_rRow[$key]) ;
            }
            foreach($this->m_rL2 as $key){
                    $strData = sprintf("%s%s%s", $strData, $strData!=null?',':'', $this->m_rRow[$key]) ;
            }
            foreach($this->m_rL3 as $key){
                    $strData = sprintf("%s%s%s", $strData, $strData!=null?',':'', $this->m_rRow[$key]) ;
            }
            foreach($this->m_rL4 as $key){
                    $strData = sprintf("%s%s%s", $strData, $strData!=null?',':'', $this->m_rRow[$key]) ;
            }            
            //die hcc values, Alter usw.
            foreach($this->m_rCustom as $key){
                    $strData = sprintf("%s%s%s", $strData, $strData!=null?',':'', $this->m_rRow[$key]) ;
            }
            
            $strOut = sprintf("%s,%s\n", $strData, $strResult) ;

        /*        
                $strOut = sprintf( "%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,"
                        .          "%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,"
                        .          "%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,"
                        .          "%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,"
                        .          "%s,%s,%s,%s,"                        
                        .          "%s\n", 
                                $this->m_rRow[self::HCC_GENDER],
                                $this->m_rRow[self::HCC_AGE],
                                $this->m_rRow[self::HCC_PRIVATE_INSURANCE],
                                $this->m_rRow[self::HCC_UNCONSCIOUS],
                                $this->m_rRow[self::HCC_DAYTIME],
                                $this->m_rRow[self::HCC_RECURRING],
                                $this->m_rRow[self::AIRWAY_COMPROMISE],
                                $this->m_rRow[self::INADEQUATE_BREATHING],
                                $this->m_rRow[self::EXSANGUATING_HAEMORRHAGE],
                                $this->m_rRow[self::UNCONTROLLABLE_MAJOR_HAEMORRHAGE],
                                $this->m_rRow[self::SHOCK],
                                $this->m_rRow[self::CURRENTLY_FITTING],
                                $this->m_rRow[self::UNRESPONSIVE_CHILD],
                                $this->m_rRow[self::STRIDOR],
                                $this->m_rRow[self::HYPOGLYCAEMIA],
                                $this->m_rRow[self::ALTERED_CONSCIOUS_LEVEL],
                                $this->m_rRow[self::HOT_ADULT],
                                $this->m_rRow[self::HOT_CHILD],
                                $this->m_rRow[self::VERY_HOT_ADULT],
                                $this->m_rRow[self::FACIAL_OEDEMA],
                                $this->m_rRow[self::OEDEMA_OF_THE_LONGUE],              //20
                                $this->m_rRow[self::UNABLE_TO_TALK_IN_SENTENCES],
                                $this->m_rRow[self::MARKED_TACHYCARDIA],
                                $this->m_rRow[self::SIGNIFICANT_MECHANISM_OF_INJURY],
                                $this->m_rRow[self::ABNORMAL_PULSE],
                                $this->m_rRow[self::LOW_SAO2],
                                $this->m_rRow[self::WIDESPREAD_RASH],
                                $this->m_rRow[self::SIGNIFICANT_HISTORY_OF_ALLERGY],
                                $this->m_rRow[self::HISTORY_OF_ALLERGY],
                                $this->m_rRow[self::HISTORY_OF_UNCONSCIOUSNESS],
                                $this->m_rRow[self::LOCAL_INFLAMATION],
                                $this->m_rRow[self::RECENT_PROBLEM],
                                $this->m_rRow[self::RECENT_MILD_PAIN_OR_ITCH],
                                $this->m_rRow[self::SEVERE_PAIN_OR_ITCH],
                                $this->m_rRow[self::VERY_LOW_SAO2],
                                $this->m_rRow[self::DEFORMITY],
                                $this->m_rRow[self::GROSS_DEFORMITY],
                                $this->m_rRow[self::OPEN_FRACTURE],
                                $this->m_rRow[self::SWELLING],
                                $this->m_rRow[self::MODERATE_PAIN_OR_ITCH],
                                $this->m_rRow[self::WARMTH],
                                $this->m_rRow[self::COLD],                             //40
                                $this->m_rRow[self::UNSTOPPABLE_MINOR_HAEMORRAGE],
                                $this->m_rRow[self::NEW_NEUROLOGICAL_DEFICIT],
                        $strResult);
    */            
                $strOut = preg_replace("/,/", $cDelimiter, $strOut) ;  //z.B: Komma in Space
                //$strOut = preg_replace("/0\./", ".", $strOut) ;  //aus 0.1 wird .1
                //printf("%s<br>", $strOut) ;
                file_put_contents($strDataFilename, $strOut, FILE_APPEND) ;
                //das Ergebnisfile wird aus der Einstufungsspalte erstellt.                
                //$strOut = preg_replace("/#/", $cDelimiter, $strOut) ;
                //printf("%s<br>", $strOut) ;
                // obsolet(alles wird jetzt in ein File gespeichert): file_put_contents($strResultFilename, $strOut, FILE_APPEND) ;                
            }
        }
        
    }

    
    $oGen = new Gen() ;
    if(isset($_GET["EVAL"])){        
        $oGen->evalVector($_GET["VECTOR"]) ;
        return ;
    }

    $nRows = $_GET['ROWS'] ;
    if(0 == $nRows) $nRows = 2500 ;
    
    $Level = null ;
    if(isset($_GET['LEVEL']) ){
        $Level = $_GET['LEVEL'] ; //aufrufer kann(zum Testen) nur ein Level anfordern.
    }
    
    
 
     
         
    if(isset($_GET["MTS"])){
        $oGen = new MTS(isset($_GET["DEBUG"])) ;
        
        //$oGen->getRow() ;
        if( $Level ){
            $oGen->generateBatch($nRows / 10, $Level) ;  
        }else{
            $oGen->generateBatch($nRows / 10, __LEVEL1__) ;        //10%            
            $oGen->generateBatch($nRows / 10, __LEVEL2__) ;        //10%  
            $oGen->generateBatch($nRows /  5, __LEVEL3__) ;        //20%  
            $oGen->generateBatch($nRows /  2, __LEVEL4__);         //50%
            $oGen->generateBatch($nRows / 10, __HYPOCHONDRIAC__);  //10%
            $oGen->genTrainingExtra1(1000) ;
            $oGen->genTrainingExtra2(1000) ;
            $oGen->genTrainingExtra3(1000) ;
            $oGen->genTrainingExtra4(1000) ;
  
        }
        $oGen->classify() ;
        //$oGen->dump() ;
        $oGen->toFile("training", " ", 1) ;
        
        $oGen = new MTS(isset($_GET["DEBUG"])) ;
        //testdaten 10% der trainingsdaten
        $nRows = $nRows / 10 ;
        $oGen->generateBatch($nRows / 10, __LEVEL1__) ;        //10%       
        $oGen->generateBatch($nRows / 10, __LEVEL2__) ;        //10%  
        $oGen->generateBatch($nRows /  5, __LEVEL3__) ;        //20%  
        $oGen->generateBatch($nRows /  2, __LEVEL4__);         //50%
        $oGen->generateBatch($nRows / 10, __HYPOCHONDRIAC__);  //10%
        $oGen->genTrainingExtra1(250) ;
        $oGen->genTrainingExtra2(250) ;
        $oGen->genTrainingExtra3(250) ;
        $oGen->genTrainingExtra4(250) ;
        $oGen->classify() ;
        $oGen->toFile("test", " ", 1) ;
        
        return ;
    }
    //
    /*else
    {     
        
    if(1 == $nRows){
        $oGen->generate($nRows - $nRows/5 - $nRows/10) ;
        $oGen->classify() ;
        //$oGen->dump() ;
        $oGen->toFile("training", " ") ;
        $nRows = $nRows / 20 ; //Testdaten sind damit deutlich weniger als Trainingsdaten
        $oGen = new Gen() ;        
        $oGen->generate($nRows - $nRows/5 - $nRows/10) ;
        $oGen->classify(null) ;
        //$oGen->dump() ;
        $oGen->toFile("test", " ") ;
        return ;
    }
    
    
    
    
    
    $oGen->genAverageJoe($nRows/5) ; //20%
    $oGen->genHypochondriac($nRows/10) ; //10%
    
    $oGen->generate($nRows - $nRows/5 - $nRows/10) ;
    $oGen->classify() ;
    //$oGen->dump() ;
    $oGen->toFile("training", " ") ;
    $nRows = $nRows / 20 ; //Testdaten sind damit deutlich weniger als Trainingsdaten
    $oGen = new Gen() ;
    $oGen->genAverageJoe($nRows/5) ; //20%
    $oGen->genHypochondriac($nRows/10) ; //10%
    $oGen->generate($nRows - $nRows/5 - $nRows/10) ;
    $oGen->classify() ;
    //$oGen->dump() ;
    $oGen->toFile("test", " ") ;
    
    }
    */
    //Dialog generieren
    if(isset($_GET["GENDIALOG"])){
        print("GENDIALOG<br>") ;
        $oGen = new MTS(isset($_GET["DEBUG"])) ;
        print($oGen->asHTMLDlg()) ;
    }
    
?>
