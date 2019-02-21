<?
    define("__HYPOCHONDRIAC__", 0) ;
    define("__AVERAGE_JOE__", 1) ;
    define("__NEEDY__", 2) ;
    define("__URGENT__", 3) ;
    
    
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
                     $strResult = "1.0,0.0,0.0,0.0,0.0" ;
                     break ;
                 case 2:
                     $strResult = "0.0,1.0,0.0,0.0,0.0" ;
                     break ;
                 case 3:
                     $strResult = "0.0,0.0,1.0,0.0,0.0" ;
                     break ;
                 case 4:
                     $strResult = "0.0,0.0,0.0,1.0,0.0" ;
                     
                     break ;
                 case 5:
                     $strResult = "0.0,0.0,0.0,0.0,1.0" ;
                     
                     break ;
                 default:       
                     $strResult = "0.0,0.0,0.0,0.0,1.0" ;
                     
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
    
    class MTS extends gen{
        private $m_oD ; //Discriminators
        private $m_oP ; //Pain Values
        private $m_oT ; //Temperature Values
        
        const ON  = 2 ;
        const OFF = 1 ;
        //Custom von hcc ausgedacht:
        const HCC_GENDER                        = "HCC_GENDER";
        const HCC_UNCONSCIOUS                   = "HCC_UNCONSCIOUS";
        const HCC_DAYTIME                       = "HCC_DAYTIME"; //morgens, mittags ... 1-4
        const HCC_RECURRING                     = 'HCC_RECURRING' ;
        const HCC_PRIVATE_INSURANCE             = 'HCC_PRIVATE_INSURANCE' ;
        const HCC_AGE                           = 'HCC_AGE' ;        
        const AIRWAY_COMPROMISE                 = "AIRWAY_COMPROMISE";//ab hier Standard MTS
        const INADEQUATE_BREATHING              = "INADEQUATE_BREATHING";
        const EXSANGUATING_HAEMORRHAGE          = "EXSANGUATING_HAEMORRHAGE";
        const UNCONTROLLABLE_MAJOR_HAEMORRHAGE  = "UNCONTROLLABLE_MAJOR_HAEMORRHAGE";
        
        const SHOCK                             = "SHOCK";
        const CURRENTLY_FITTING                 = "CURRENT_FITTING";
        const UNRESPONSIVE_CHILD                = "UNRESPONSIVE_CHILD";
        const STRIDOR                           = "STRIDOR";
        const HYPOGLYCAEMIA                     = "HYPOGLYCAEMIA";
        const PAIN                              = "PAIN";
        const RECENT_PAIN                       = "RECENT_PAIN";
        const ITCH                              = "ITCH" ;
        const SEVERE_ITCH                       = "SEVERE_ITCH" ;
        const ALTERED_CONSCIOUS_LEVEL           = "ALTERED_CONSCIOUS_LEVEL";
        
        const HOT_CHILD                         = "HOT_CHILD";
        const VERY_HOT_ADULT                    = "VERY_HOT_ADULT";
        const FACIAL_OEDEMA                     = "FACIAL_OEDEMA";
        const OEDEMA_OF_THE_LONGUE              = "OEDEMA_OF THE_LONGUE";
        const UNABLE_TO_TALK_IN_SENTENCES       = "UNABLE_TO_TALK_IN_SENTENCES";
        const MARKED_TACHYCARDIA                = "MARKED_TACHYCARDIA";        
        const SIGNIFICANT_MECHANISM_OF_INJURY                = "SIGNIFICANT_MECHANISM_OF_INJURY";
        const ABNORMAL_PULSE                    = "ABNORMAL_PULSE";
        const LOW_SAO2                          = "LOW_SAO2";
        const WIDESPREAD_RASH                   = "WIDESPREAD_RASH";
        
        const HISTORY_OF_ALLERGY                = "HISTORY_OF_ALLERGY";
        const NEW_NEURO_DEFICIT                 = "NEW_NEURO_DEFICIT";
        const FRACTURE                          = "FRACTURE";   //OPEN
        const HISTORY_OF_UNCONSCIOUSNESS        = "HISTORY_OF_UNCONSCIOUSNESS" ;
        const TEMPERATURE                       = "TEMPERATURE"; //hot, cold, warm
        const LOCAL_INFLAMATION                 = "LOCAL_INFLAMATION";
        const RECENT                            = "RECENT_SYMPTOMS" ; //Beschwerden bestehen schon länger
        const RECENT_MILD_PAIN_OR_ITCH          = "RECENT_MILD_PAIN_OR_ITCH"; //on/off
        const VERY_LOW_SAO2                     = "VERY_LOW_SAO2";
        const DEFORMITY                         = "DEFORMITY";        
        const SWELLING                          = "SWELLING";
        const RECENT_MILD_PAIN                  = "RECENT_MILD_PAIN";
        const WARMTH                            = "WARMTH";
        const UNSTOPPABLE_MINOR_HAEMORRAGE      = "UNSTOPPABLE_MINOR_HAEMORRAGE";
        private $m_rCritical = array( ) ; //alle kritischen Merkmale
        private $m_rNeedy    = array( ) ; //Merkmale, für Level 2/3
        
        function __construct($DEBUG) {
            //$this->init() ;      
            $this->m_DEBUG = $DEBUG ;
            array_push($this->m_rNeedy, self::FACIAL_OEDEMA) ;            
            array_push($this->m_rNeedy, self::UNCONTROLLABLE_MAJOR_HAEMORRHAGE) ;
            array_push($this->m_rNeedy, self::UNABLE_TO_TALK_IN_SENTENCES) ;
            array_push($this->m_rNeedy, self::PAIN) ;
            array_push($this->m_rNeedy, self::ABNORMAL_PULSE) ;
            array_push($this->m_rNeedy, self::LOW_SAO2) ;
            array_push($this->m_rNeedy, self::WIDESPREAD_RASH) ;
            array_push($this->m_rNeedy, self::UNRESPONSIVE_CHILD) ;
            array_push($this->m_rNeedy, self::DEFORMITY) ;
            array_push($this->m_rNeedy, self::SWELLING) ;
            
            
            array_push($this->m_rCritical, self::HCC_UNCONSCIOUS) ;
            array_push($this->m_rCritical, self::AIRWAY_COMPROMISE) ;
            array_push($this->m_rCritical, self::VERY_HOT_ADULT) ;
            array_push($this->m_rCritical, self::STRIDOR) ;
            array_push($this->m_rCritical, self::HYPOGLYCAEMIA) ;
            array_push($this->m_rCritical, self::EXSANGUATING_HAEMORRHAGE) ;
            array_push($this->m_rCritical, self::INADEQUATE_BREATHING) ;
            array_push($this->m_rCritical, self::CURRENTLY_FITTING) ;
            array_push($this->m_rCritical, self::UNRESPONSIVE_CHILD) ;
            array_push($this->m_rCritical, self::SHOCK) ;
            array_push($this->m_rCritical, self::SIGNIFICANT_MECHANISM_OF_INJURY) ;
            //erst mal nicht: array_push($this->m_rCritical, self::TEMPERATURE) ;
            
     
            
            
        }
        
        private function init(){
            
            $this->m_oT = new MTS_TEMPERATURE_VALUES() ;
            $this->m_oP = new MTS_PAIN_VALUES() ;
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
            if( self::ON == $rRow[self::HOT_CHILD] && 
                    ( 
                        self::ON == $rRow[self::VERY_HOT_ADULT]  
                    )
               ){
                    $rRow[self::VERY_HOT_ADULT] = self::OFF ;
                }    
            if( self::ON == $rRow[self::RECENT_MILD_PAIN_OR_ITCH] && 
                    ( 
                        $rRow[self::PAIN] > 1  
                    )
               ){
                    $rRow[self::RECENT_MILD_PAIN_OR_ITCH] = self::OFF ;
                } 
                
            if( self::ON == $rRow[self::RECENT_MILD_PAIN_OR_ITCH] && 
                    ( 
                        self::ON == $rRow[self::ITCH]  
                    )
               ){
                    $rRow[self::RECENT_MILD_PAIN_OR_ITCH] = self::OFF ;
                } 
                
            if( self::ON == $rRow[self::RECENT_MILD_PAIN_OR_ITCH] && 
                    ( 
                        $rRow[self::PAIN] > 1  
                    )
               ){
                    $rRow[self::RECENT_MILD_PAIN_OR_ITCH] = self::OFF ;
                }        
                
        }
        
        public function genRandom(){
            $rRow = array() ;
            $rRow[self::HCC_GENDER]                         = mt_rand(1, 2);
            $rRow[self::HCC_UNCONSCIOUS]                    = mt_rand(1, 2);            
            $rRow[self::HCC_PRIVATE_INSURANCE]              = mt_rand(1, 2);
            $rRow[self::HCC_AGE              ]              = mt_rand(1, 110);
            $rRow[self::HCC_DAYTIME    ]                    = mt_rand(1, 4);
            $rRow[self::AIRWAY_COMPROMISE]                  = mt_rand(1, 2);
            $rRow[self::INADEQUATE_BREATHING]               = mt_rand(1, 2) ;
            $rRow[self::EXSANGUATING_HAEMORRHAGE]           = mt_rand(1, 2);
            $rRow[self::UNCONTROLLABLE_MAJOR_HAEMORRHAGE]   = mt_rand(1, 2);
            $rRow[self::SHOCK]                              = mt_rand(1, 2);
            
            $rRow[self::CURRENTLY_FITTING]                  = mt_rand(1, 2);
            $rRow[self::UNRESPONSIVE_CHILD]                 = mt_rand(1, 2);
            $rRow[self::STRIDOR]                            = mt_rand(1, 2);
            $rRow[self::HYPOGLYCAEMIA]                      = mt_rand(1, 2);
            $rRow[self::SEVERE_ITCH]                        = mt_rand(1, 2);
            $rRow[self::PAIN]                               = MTS_PAIN_VALUES::getRand();
            $rRow[self::RECENT_PAIN]                        = mt_rand(1, 2);
            $rRow[self::ITCH]                               = mt_rand(1, 2);
            $rRow[self::ALTERED_CONSCIOUS_LEVEL]            = mt_rand(1, 2);
            $rRow[self::HOT_CHILD]                          = mt_rand(1, 2);
            $rRow[self::VERY_HOT_ADULT]                     = mt_rand(1, 2);
            
            $rRow[self::FACIAL_OEDEMA]                      = mt_rand(1, 2);
            $rRow[self::OEDEMA_OF_THE_LONGUE]               = mt_rand(1, 2);
            $rRow[self::UNABLE_TO_TALK_IN_SENTENCES]        = mt_rand(1, 2);
            $rRow[self::MARKED_TACHYCARDIA]                 = mt_rand(1, 2);
            $rRow[self::SIGNIFICANT_MECHANISM_OF_INJURY]                 = mt_rand(1, 2);
            $rRow[self::ABNORMAL_PULSE]                     = mt_rand(1, 2);
            $rRow[self::VERY_LOW_SAO2]                      = mt_rand(1, 2);
            if( 2 == $rRow[self::VERY_LOW_SAO2] ){ //dann ist auch LOW true.
                $rRow[self::LOW_SAO2]                       = self::ON ;
            }
            else{
                $rRow[self::LOW_SAO2]                       = mt_rand(1, 2);
            }
            
            $rRow[self::WIDESPREAD_RASH]                    = mt_rand(1, 2);
            $rRow[self::HISTORY_OF_ALLERGY]                 = mt_rand(1, 2);
            
            $rRow[self::NEW_NEURO_DEFICIT]                  = mt_rand(1, 2);
            $rRow[self::FRACTURE]                           = mt_rand(1, 2);
            $rRow[self::HISTORY_OF_UNCONSCIOUSNESS]         = mt_rand(1, 2);
            
            $rRow[self::WARMTH]                             = mt_rand(1, 2);
            $rRow[self::LOCAL_INFLAMATION]                  = mt_rand(1, 2); //ist L4
            $rRow[self::RECENT]                             = mt_rand(1, 2);
            $rRow[self::RECENT_MILD_PAIN_OR_ITCH]           = mt_rand(1, 2);
            $rRow[self::UNSTOPPABLE_MINOR_HAEMORRAGE]       = mt_rand(1, 2);
            $rRow[self::MODERATE_PAIN           ]           = mt_rand(1, 2);
            $rRow[self::DEFORMITY]                          = mt_rand(1, 2);
            $rRow[self::SWELLING]                           = mt_rand(1, 2); 
            $rRow[self::WARMTH]                             = mt_rand(1, 2); 
            return $rRow ;
        }
        
        
        /*
         * Normal generieren, dann aber bestimmte Werte prüfen und ggf. hochsetzen
         */
        public function genUrgent($nRows){
            
            $iIdx = 0 ; //zum Würfeln.
            if($this->m_DEBUG){
                printf("<br>%s:%d Generating [%d] rows.<br>", __METHOD__, __LINE__, $nRows) ;
            }
            for($i=0;$i<$nRows;$i++){
                $rRow = $this->genRandom( ) ; //zunächst wird ein beliebiger Datensatz generiert, dann aufgepimpt:
                //wenigstens eins von diesen muss dann gesetzt sein:
                //kein Urgent generiert                
                if( false == $this->isUrgent($rRow) ){
                    $iIdx = mt_rand(0, count($this->m_rCritical) - 1); //so viele Merkmale machen Urgent aus
                    $rRow[$this->m_rCritical[$iIdx]] = self::ON ;
                }
                //aber auch: einige Merkmale schliessen sich gegenseitig aus:
                //HOT_CHILD / HOT_ADULT
                //Wenn Alter > 13, dann kann HOT_CHILD nicht sein
                //Wenn bewusstlos, dann kann Talking... nicht sein
                if( self::ON == $rRow[self::HOT_CHILD] &&  self::ON == $rRow[self::VERY_HOT_ADULT] ){
                   if( $rRow[self::HCC_AGE] >= 13 ){ //adult
                       $rRow[self::HOT_CHILD] = self::OFF ; //zurücksetzen
                   }else{
                       $rRow[self::VERY_HOT_ADULT] = self::OFF ;
                   }
                }
                if( self::ON == $rRow[self::HOT_CHILD] &&  $rRow[self::HCC_AGE] >= 13 ){
                    $rRow[self::VERY_HOT_ADULT] = self::ON ;
                    $rRow[self::HOT_CHILD] = self::OFF ;
                }
                
                if( self::ON == $rRow[self::HCC_UNCONSCIOUS] && self::ON == $rRow[self::UNABLE_TO_TALK_IN_SENTENCES] ){
                    $rRow[self::UNABLE_TO_TALK_IN_SENTENCES] = 1 ;
                }                
                array_push($this->m_rData, $rRow) ;
            }            
            return $rRow ;
        }
        
        /*
         * Normal generieren, dann aber bestimmte Werte prüfen und ggf. hochsetzen
         */
        public function genNeedy($nRows){            
            $iIdx = 0 ; //zum Würfeln.
            $i = 0 ;
            $k = 0 ;
            if($this->m_DEBUG){
                printf("<br>%s:%d Generating [%d] rows.<br>", __METHOD__, __LINE__, $nRows) ;
            }
            $rRow = $this->genRandom($nRows) ; //zunächst wird ein Urgent Datensatz generiert, dann abgepimpt:
        
            for($i=0;$i<$nRows;$i++){                
                //von diesem werden die kritischen Merkmale auf OFF gesetzt,
                //andere Temp, Schmerz usw. werden an die Grenze gehoben.                
               
                for($k=1; $k<sizeof($this->m_rNeedy); $k++){
                    printf("<br>%d LEN Needy: %d", $i, sizeof($this->m_rNeedy)) ;
                    $rRow[$this->m_rNeedy[k]] = self::OFF ;
                }  
                
                $iIdx = mt_rand(0, count($this->m_rNeedy) - 1); //so viele Merkmale machen Needy aus, eines nehmen wir.
                $rRow[$this->m_rCritical[$iIdx]] = self::ON ;
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
            if($this->m_DEBUG){
                printf("<br>%s:%d Generating [%d] rows.<br>", __METHOD__, __LINE__, $nRows) ;
            }
            
            for($i=0;$i<$nRows;$i++){
                $rRow = $this->genRandom( ) ; //zunächst wird ein beliebiger Datensatz generiert, dann aufgepimpt:                
                $rRow[self::HCC_UNCONSCIOUS]                    = self::OFF ; //nicht bewusstlos
                $rRow[self::HCC_DAYTIME    ]                    = mt_rand(1, 2); //nur morgens, mittags
                $rRow[self::AIRWAY_COMPROMISE]                  = self::OFF ;
                $rRow[self::INADEQUATE_BREATHING]               = self::OFF ; //mt_rand(1, 2);
                $rRow[self::EXSANGUATING_HAEMORRHAGE]           = self::OFF ;
                $rRow[self::UNCONTROLLABLE_MAJOR_HAEMORRHAGE]   = self::OFF ;
                $rRow[self::SHOCK]                              = self::OFF ;
                $rRow[self::CURRENTLY_FITTING]                  = self::OFF ;
                $rRow[self::UNRESPONSIVE_CHILD]                 = self::OFF ; //mt_rand(1, 2);
                $rRow[self::STRIDOR]                            = self::OFF ; //mt_rand(1, 2);
                
                $rRow[self::HYPOGLYCAEMIA]                      = self::OFF ;
                $rRow[self::PAIN]                               = MTS_PAIN_VALUES::getRand();
                $rRow[self::RECENT_PAIN]                        = mt_rand(1, 2);
                $rRow[self::ITCH]                               = mt_rand(1, 2);
                $rRow[self::SEVERE_ITCH]                        = self::OFF ;
                $rRow[self::ALTERED_CONSCIOUS_LEVEL]            = self::OFF ;
                $rRow[self::HOT_CHILD]                          = self::OFF ;
                $rRow[self::VERY_HOT_ADULT]                     = self::OFF ;
                $rRow[self::OEDEMA_OF_THE_LONGUE]               = self::OFF ;
                $rRow[self::FACIAL_OEDEMA]                      = self::OFF ;
                $rRow[self::UNABLE_TO_TALK_IN_SENTENCES]        = self::OFF ;
                
                $rRow[self::MARKED_TACHYCARDIA]                 = self::OFF ;
                $rRow[self::SIGNIFICANT_MECHANISM_OF_INJURY]                 = self::OFF ;
                $rRow[self::ABNORMAL_PULSE]                     = self::OFF ;
                $rRow[self::LOW_SAO2]                           = self::OFF ;
                $rRow[self::VERY_LOW_SAO2]                      = self::OFF ;
                $rRow[self::WIDESPREAD_RASH]                    = mt_rand(1, 2);
                $rRow[self::HISTORY_OF_ALLERGY]                 = mt_rand(1, 2);
                $rRow[self::NEW_NEURO_DEFICIT]                  = mt_rand(1, 2);
                $rRow[self::FRACTURE]                           = mt_rand(1, 2);
                $rRow[self::HISTORY_OF_UNCONSCIOUSNESS]         = mt_rand(1, 2);
                $rRow[self::TEMPERATURE]                        = MTS_TEMPERATURE_VALUES::getNormal();
                
                $rRow[self::LOCAL_INFLAMATION]                  = mt_rand(1, 2);
                $rRow[self::RECENT]                             = mt_rand(1, 2);
                $rRow[self::DEFORMITY]                          = self::OFF ;
                $rRow[self::SWELLING]                           = mt_rand(1, 2); 
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
            for($i=0;$i<$nRows;$i++){
            $rRow = $this->genRandom() ;
            $rRow[self::HCC_UNCONSCIOUS]                            = self::OFF ;
            $rRow[self::HCC_DAYTIME    ]                            = mt_rand(1, 2); //nur morgens, mittags
            $rRow[self::AIRWAY_COMPROMISE]                          = self::OFF ;
            $rRow[self::INADEQUATE_BREATHING]                       = self::OFF;
            $rRow[self::EXSANGUATING_HAEMORRHAGE]                   = self::OFF;
            $rRow[self::UNCONTROLLABLE_MAJOR_HAEMORRHAGE]           = self::OFF;
            $rRow[self::SHOCK]                                      = self::OFF;
            $rRow[self::CURRENTLY_FITTING]                          = self::OFF;
            $rRow[self::UNRESPONSIVE_CHILD]                         = self::OFF;
            $rRow[self::STRIDOR]                                    = self::OFF;
            $rRow[self::HYPOGLYCAEMIA]                              = self::OFF ;
            $rRow[self::PAIN]                                       = self::OFF;
            $rRow[self::RECENT_PAIN]                                = self::ON;
            $rRow[self::ITCH]                                       = self::OFF ;
            $rRow[self::ALTERED_CONSCIOUS_LEVEL]                    = self::OFF ;
            $rRow[self::HOT_CHILD]                                  = self::OFF ;
            $rRow[self::VERY_HOT_ADULT]                             = self::OFF ;
            $rRow[self::OEDEMA_OF_THE_LONGUE]                       = self::OFF ;
            $rRow[self::FACIAL_OEDEMA]                              = self::OFF ;
            $rRow[self::UNABLE_TO_TALK_IN_SENTENCES]                = self::OFF ;
            $rRow[self::MARKED_TACHYCARDIA]                         = self::OFF ;
            $rRow[self::SIGNIFICANT_MECHANISM_OF_INJURY]                         = self::OFF ;
            $rRow[self::ABNORMAL_PULSE]                             = self::OFF ;
            $rRow[self::LOW_SAO2] = $rRow[self::VERY_LOW_SAO2]      = self::OFF ;
            $rRow[self::WIDESPREAD_RASH]                            = self::OFF ;
            $rRow[self::HISTORY_OF_ALLERGY]                         = self::OFF ;
            $rRow[self::NEW_NEURO_DEFICIT]                          = self::OFF ;
            $rRow[self::FRACTURE]                                   = self::OFF ;
            $rRow[self::HISTORY_OF_UNCONSCIOUSNESS]                 = self::OFF ;
            $rRow[self::TEMPERATURE]                                = MTS_TEMPERATURE_VALUES::getNormal();                       
            $rRow[self::LOCAL_INFLAMATION]                          = self::OFF ;
            $rRow[self::RECENT]                                     = self::ON;
            $rRow[self::RECENT_MILD_PAIN_OR_ITCH]                   = self::ON;
            $rRow[self::DEFORMITY]                                  = self::OFF ;
            $rRow[self::SWELLING]                                   = self::OFF ;
            array_push($this->m_rData, $rRow) ;
            }
        }
        
        /* CLASS MTS
         * Muss angepasst werden
         */
        public function isUrgent($rRow){
            if($this->m_DEBUG){                
                for($i=0;$i<sizeof($this->m_rCritical);$i++){
                    printf("[%s] = [%d]<br>", $this->m_rCritical[$i], $rRow[$this->m_rCritical[$i]]) ;
                }
            }
            //printf("<b>TEMP HOT IS [%d]</b><br>", MTS_TEMPERATURE_VALUES::HOT) ;
            $bRet = ( $rRow[self::HCC_UNCONSCIOUS] > 1 || 
                     $rRow[self::AIRWAY_COMPROMISE] > 1 || 
                     $rRow[self::VERY_HOT_ADULT] > 1 ||
                     $rRow[self::STRIDOR] > 1 ||
                     $rRow[self::HYPOGLYCAEMIA] > 1 ||
                     $rRow[self::EXSANGUATING_HAEMORRHAGE] > 1 ||
                     $rRow[self::INADEQUATE_BREATHING] > 1 ||
                     $rRow[self::CURRENTLY_FITTING] > 1 ||
                     $rRow[self::UNRESPONSIVE_CHILD] > 1 
                    || $rRow[self::SHOCK] > 1 
                    || $rRow[self::SIGNIFICANT_MECHANISM_OF_INJURY] >1 
                    || $rRow[self::TEMPERATURE] > MTS_TEMPERATURE_VALUES::HOT
                    ) ;
            if($this->m_DEBUG){
                printf("[%s] Ergebnis is [%s]<br>", __METHOD__, $bRet==true?"URGENT":"NICHT URGENT") ;
            }
            return $bRet ;
        } 
        /*
         * no serious symptoms 
         * Level 5
         */
        public function isHypochondriac($rRow){
              return ( $rRow[self::HCC_UNCONSCIOUS] <= 1 || 
                      $rRow[self::AIRWAY_COMPROMISE] <= 1 || 
                      $rRow[self::EXSANGUATING_HAEMORRHAGE] <= 1 || 
                      $rRow[self::VERY_HOT_ADULT] <= 1 ||
                      $rRow[self::HYPOGLYCAEMIA] <= 1 ||
                     $rRow[self::UNRESPONSIVE_CHILD] <= 1 || 
                      $rRow[self::SHOCK] <= 1 || 
                      $rRow[self::SIGNIFICANT_MECHANISM_OF_INJURY] <= 1 ||
                     $rRow[self::TEMPERATURE] < $m_T->HOT) ;        
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
            
            return ( 
                      $rRow[self::PAIN] == $m_P->SEVERE ||
                      $rRow[self::UNCONTROLLABLE_MAJOR_HAEMORRHAGE] > 1 ||
                      $rRow[self::ALTERED_CONSCIOUS_LEVEL] > 1 ||
                      $rRow[self::HOT_CHILD] > 1 ||
                      $rRow[self::TEMPERATURE] == $m_T->HOT ||
                      $rRow[self::VERY_HOT_ADULT] > 1 ||
                      $rRow[self::FACIAL_OEDEMA] > 1 ||
                      $rRow[self::OEDEMA_OF_THE_LONGUE] > 1 ||
                      $rRow[self::UNABLE_TO_TALK_IN_SENTENCES] > 1 ||
                      $rRow[self::MARKED_TACHYCARDIA] > 1 ||
                      $rRow[self::SIGNIFICANT_MECHANISM_OF_INJURY] > 1 ||
                      $rRow[self::ABNORMAL_PULSE] > 1 ||
                      $rRow[self::VERY_LOW_SAO2] > 1
                    ) ;        
        }
        /*
         * nicht bewusslos, oder anderweitig schwere Erkrankung.
         */
        public function isLevel3($rRow){
              return ( $rRow[self::HCC_UNCONSCIOUS] <= 1 || $rRow[self::AIRWAY_COMPROMISE] <= 1 || $rRow[self::VERY_HOT_ADULT] <= 1 ||
                     $rRow[self::UNRESPONSIVE_CHILD] <= 1 || $rRow[self::SHOCK] <= 1 || $rRow[self::SIGNIFICANT_MECHANISM_OF_INJURY] <= 1 ||
                     $rRow[self::TEMPERATURE] < $m_T->HOT) ;        
        }
        /* CLASS MTS
         * Keine der relevanten Merkmale vorhanden
         */
        public function isLevel4($rRow){
             if( $this->isHypoChondriac($rRow) ){
                 //wenn die Person ein Level5 ist, dann bleibt er das.
                 return false ;
             }
              return ( $rRow[self::HCC_UNCONSCIOUS] <= 1 || 
                      $rRow[self::AIRWAY_COMPROMISE] <= 1 || 
                      $rRow[self::VERY_HOT_ADULT] <= 1 ||
                      $rRow[self::UNRESPONSIVE_CHILD] <= 1 || 
                      $rRow[self::SHOCK] <= 1 || 
                      $rRow[self::SIGNIFICANT_MECHANISM_OF_INJURY] <= 1 ||
                      $rRow[self::TEMPERATURE] < $m_T->HOT) ;        
        }
        /*
         * Keine der relevanten Merkmale vorhanden,
         * und Wiederkehrer.
         */
        public function isLevel5($rRow){
              return ( ($rRow[self::HCC_UNCONSCIOUS] <= self::OFF || 
                      $rRow[self::AIRWAY_COMPROMISE] <= self::OFF || 
                      $rRow[self::VERY_HOT_ADULT] <= self::OFF ||
                      $rRow[self::UNRESPONSIVE_CHILD] <= self::OFF || 
                      $rRow[self::SHOCK] <= self::OFF || 
                      $rRow[self::SIGNIFICANT_MECHANISM_OF_INJURY] <= self::OFF ||
                      $rRow[self::TEMPERATURE] < $m_T->HOT)
                      && ( $rRow[self::RECENT_PAIN] == self::ON
                      && $rRow[RECENT_MILD_PAIN_OR_ITCH] == self::ON
                      && $rRow[RECENT_MILD_PAIN_OR_ITCH] == self::ON)
                      
                      ) ;        
        }
        
        //nur f. Eval
        public function isAverageJoe($rRow){
            
                
        } 
        
        
        
        
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
                    case( __NEEDY__ ): //so ungefähr level 3 und 4
                        return $this->genNeedy($nRows) ;
                        break ;
                    case( __URGENT__ ):
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
                $this->m_rRow['EINSTUFUNG'] = 5 ;
                if( $this->m_DEBUG ){
                    printf("<hr>%s:%d<br>", __METHOD__, __LINE__) ;
                    $rElements = array_keys($this->m_rRow) ;
                    for($i=0;$i<sizeof($rElements);$i++){
                        printf("[%s] = [%d]<br>", $rElements[$i], $this->m_rRow[$rElements[$i]]) ;
                    }
                    print("<br>") ;
                }
                
                
                printf("<br>Checking for Urgent ZEILE [%d]<br>", __LINE__) ;
                if($this->isUrgent($this->m_rRow)){                    
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
                if( $this->m_DEBUG ){
                    printf("<b style=\"color:white;background-color:blue\">EINGESTUFT als [%d]</b><br>", $this->m_rRow['EINSTUFUNG']) ;
                    print("<hr>") ;
                } 
                
                if($this->isLevel2($this->m_rRow)){                    
                    $this->m_rRow['EINSTUFUNG'] = 2 ;
                    if( $this->m_DEBUG ){
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "LEVEL_2", __LINE__) ;
                    }
                    continue ;
                }                
                
                //wir brauchen die 3, 4 und 5 Kategorie
                if($this->isAverageJoe($this->m_rRow)){                    
                    $this->m_rRow['EINSTUFUNG'] = 3 ;
                    if( $this->m_DEBUG ){                        
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "__AVERAGE_JOE__", __LINE__) ;
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
                
                if($this->isHypochondriac($this->m_rRow)){                    
                    $this->m_rRow['EINSTUFUNG'] = 5 ;
                    if( $this->m_DEBUG ){
                        printf("EINSTUFUNG %d zu %s ZEILE [%s]<br>", $this->m_rRow['EINSTUFUNG'], "HYPOCHONDER", __LINE__) ;
                    }                     
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
            unlink ( $strDataFilename ) ;
            //unlink ( $strResultFilename ) ;
            print("<br>") ;
            
            if($DEBUG){
                printf("The Vector has <b>[%d]</b> Elements.<br>", sizeof($this->m_rRow)) ;
                $rElements = array_keys($this->m_rRow) ;
                for($i=0;$i<sizeof($rElements);$i++){
                    printf("[%s]<br>", $rElements[$i]) ;
                }                
            }
            
            foreach($this->m_rData as $this->m_rRow){ //fünf Einstufungen
                switch($this->m_rRow['EINSTUFUNG']){
                 case 1:
                     $strResult = "1.0,0.0,0.0,0.0,0.0,A" ; //die Buchstaben sind nur Hilfe f. Lesen des Outputs
                     break ;
                 case 2:
                     $strResult = "0.0,1.0,0.0,0.0,0.0,B" ;
                     break ;
                 case 3:
                     $strResult = "0.0,0.0,1.0,0.0,0.0,C" ;
                     break ;
                 case 4:
                     $strResult = "0.0,0.0,0.0,1.0,0.0,D" ;                     
                     break ;
                 case 5:
                     $strResult = "0.0,0.0,0.0,0.0,1.0,E" ;                     
                     break ;
                 default:       
                     $strResult = "0.0,0.0,0.0,0.0,1.0,F" ;
                     
                }
                

                
                $strOut = sprintf( "%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,"
                        .          "%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,"
                        .          "%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,"
                        .          "%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,%s.0,"
                        .          "%s\n", 
                        $this->m_rRow[self::HCC_GENDER],
                        $this->m_rRow[self::HCC_AGE],
                        $this->m_rRow[self::HCC_UNCONSCIOUS],
                        $this->m_rRow[self::HCC_DAYTIME] ,
                        $this->m_rRow[self::HCC_PRIVATE_INSURANCE] ,
                        $this->m_rRow[self::AIRWAY_COMPROMISE] ,
                        $this->m_rRow[self::INADEQUATE_BREATHING] ,
                        $this->m_rRow[self::EXSANGUATING_HAEMORRHAGE]         ,
                        $this->m_rRow[self::UNCONTROLLABLE_MAJOR_HAEMORRHAGE] ,                        
                        $this->m_rRow[self::SHOCK]                            ,//10
                        $this->m_rRow[self::CURRENTLY_FITTING]                ,
                        $this->m_rRow[self::UNRESPONSIVE_CHILD]               ,
                        $this->m_rRow[self::STRIDOR]                          ,                         
                        $this->m_rRow[self::HYPOGLYCAEMIA]                    ,
                        $this->m_rRow[self::SEVERE_ITCH]                      ,
                        $this->m_rRow[self::PAIN]                             ,  
                        $this->m_rRow[self::RECENT_PAIN]                      ,
                        $this->m_rRow[self::ITCH]                             ,
                        $this->m_rRow[self::ALTERED_CONSCIOUS_LEVEL]          ,                         
                        $this->m_rRow[self::HOT_CHILD]                        ,//20
                        $this->m_rRow[self::VERY_HOT_ADULT]                   ,
                        $this->m_rRow[self::FACIAL_OEDEMA]                    ,
                        $this->m_rRow[self::OEDEMA_OF_THE_LONGUE]             ,
                        $this->m_rRow[self::UNABLE_TO_TALK_IN_SENTENCES]      ,
                        $this->m_rRow[self::MARKED_TACHYCARDIA]               , 
                        $this->m_rRow[self::SIGNIFICANT_MECHANISM_OF_INJURY]               ,
                        $this->m_rRow[self::ABNORMAL_PULSE]                   ,
                        $this->m_rRow[self::LOW_SAO2]                         ,
                        $this->m_rRow[self::VERY_LOW_SAO2]                    ,
                        $this->m_rRow[self::WIDESPREAD_RASH]                  , //30
                        
                        $this->m_rRow[self::HISTORY_OF_ALLERGY]               ,
                        $this->m_rRow[self::NEW_NEURO_DEFICIT]                ,
                        $this->m_rRow[self::FRACTURE]                         ,
                        $this->m_rRow[self::HISTORY_OF_UNCONSCIOUSNESS]       ,
                        $this->m_rRow[self::TEMPERATURE]                      , 
                        $this->m_rRow[self::LOCAL_INFLAMATION]                ,                         
                        $this->m_rRow[self::RECENT]                           ,
                        $this->m_rRow[self::RECENT_MILD_PAIN_OR_ITCH]         ,
                        $this->m_rRow[self::DEFORMITY]                        ,//                        
                        $this->m_rRow[self::SWELLING]                         ,//40                       
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
   
    
    $oGen = new Gen() ;
    if(isset($_GET["EVAL"])){        
        $oGen->evalVector($_GET["VECTOR"]) ;
        return ;
    }

    $nRows = $_GET['ROWS'] ;
    if(0 == $nRows) $nRows = 2500 ; 
    
    
    if(isset($_GET["MTS"])){
        $oGen = new MTS(isset($_GET["DEBUG"])) ;
        
        //$oGen->getRow() ;
        
        //$oGen->generateBatch($nRows / 10, __URGENT__) ;        //10%        
        $oGen->generateBatch($nRows /  2, __AVERAGE_JOE__);   //50%
        //$oGen->generateBatch($nRows / 10, __HYPOCHONDRIAC__); //10%
        
        //  $oGen->generateBatch($nRows /  3, __NEEDY__) ;         //30%
         
        
        $oGen->classify() ;
        //$oGen->dump() ;
        $oGen->toFile("training", " ", 1) ;
        return ;
    }
    else{        
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
    
    
?>
