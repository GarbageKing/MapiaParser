<?php

class CParser extends
{

    const BASE_URL = 'http://mapia.ua/ru/partenit/index';
    const BASE_CT = 'partenit';
    const BASE_CT_RU = 'Партенит';
    const BASE_FILENAME = 'CompaniesPartenitMapia.csv';
    
    protected $_allInfo = [];
    
    /** Main function  */
    public function run()
    {               
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
        set_time_limit(0);
        ini_set('memory_limit','-1');
        
        $categories = $this->getCategories();
        
        foreach($categories as $category){            
          
          $pagesCount = $this->getPages($category); 
          $this->getCompanies($pagesCount, $category);
        
        }            
           
    }

    public function getPageContent($url)
    {
        $ch = curl_init();
        $uagent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_USERAGENT, $uagent);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $mart = curl_exec($ch);
        curl_close($ch);

        return $mart;
    }
    
    /**
     * @return array
     */
    public function getCategories()
    {
        $html = $this->getPageContent(self::BASE_URL);
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        
        $cats = $dom->getElementsByTagName('a');
        
        $catLinks = [];  
        $catLinksFix = [];
               
        foreach ($cats as $cat) {
                
        if (strpos($cat->getAttribute('href'), self::BASE_CT) !== false){
        
        $catLink = $cat->attributes['href']->value;         
        
        $catLink = 'http://mapia.ua' . $catLink;
        
        if(strpos($catLink, 'lists')==false && strpos($catLink, 'return')==false
        && strpos($catLink, 'welcome')==false 
                && $catLink != str_replace('/index', '', self::BASE_URL)){
        $catLinks[] = $catLink;
        }
        
        }
        
        }
        
        
        $catLinksFix = array_unique($catLinks);
        
        return $catLinksFix;
        
    }
    
    public function getPages($category)
    {
        
        $html = $this->getPageContent($category);
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        
        $howMuch = 1;
        
        $numhs = $dom->getElementsByTagName('div');
        
        foreach ($numhs as $numh) {
                
        if ($numh->getAttribute('class') == 'lists-pagination'){
           
            $howMuch = $numh->childNodes[0]->nodeValue;
            $howMuch = explode(' ', $howMuch);
            $howMuch = $howMuch[count($howMuch)-2];
           
        }        
        }
        
        return $howMuch;
        
    }
    
    public function getCompanies ($pagesCount, $category)
    {
        $j = 0;
        for($i = 1; $i <= $pagesCount; $i++)
        {
            
            $html = $this->getPageContent($category . '?page=' . $i);        
            $dom = new DOMDocument();
            @$dom->loadHTML($html);
            
            $companies = $dom->getElementsByTagName('div');
            
            foreach($companies as $company)
            {
                
                if(strpos($company->getAttribute('class'), 'big-feature') !== false)
                {
                    
                    $str = $company->nodeValue;
                   
                    $info = explode ('"textFlow":"', $str);
                    $info = explode ('<a href', $info[1]);
                    $info = strip_tags ($info[0]);
                    
                    $table = [
                        '\u0410' => 'А',
                        '\u0411' => 'Б',
                        '\u0412' => 'В',
                        '\u0413' => 'Г',
                        '\u0414' => 'Д',
                        '\u0415' => 'Е',                        
                        '\u0416' => 'Ж',
                        '\u0417' => 'З',
                        '\u0418' => 'И',
                        '\u0419' => 'Й',
                        '\u041a' => 'К',
                        '\u041b' => 'Л',
                        '\u041c' => 'М',
                        '\u041d' => 'Н',
                        '\u041e' => 'О',
                        '\u041f' => 'П',
                        '\u0420' => 'Р',
                        '\u0421' => 'С',
                        '\u0422' => 'Т',
                        '\u0423' => 'У',
                        '\u0424' => 'Ф',
                        '\u0425' => 'Х',
                        '\u0426' => 'Ц',
                        '\u0427' => 'Ч',
                        '\u0428' => 'Ш',
                        '\u0429' => 'Щ',
                        '\u042a' => 'Ъ',
                        '\u042b' => 'Ы',
                        '\u042c' => 'Ь',
                        '\u042d' => 'Э',
                        '\u042e' => 'Ю',
                        '\u042f' => 'Я',
                        
                        '\u0430' => 'а',
                        '\u0431' => 'б',
                        '\u0432' => 'в',
                        '\u0433' => 'г',
                        '\u0434' => 'д',
                        '\u0435' => 'е',                        
                        '\u0436' => 'ж',
                        '\u0437' => 'з',
                        '\u0438' => 'и',
                        '\u0439' => 'й',
                        '\u043a' => 'к',
                        '\u043b' => 'л',
                        '\u043c' => 'м',
                        '\u043d' => 'н',
                        '\u043e' => 'о',
                        '\u043f' => 'п',
                        '\u0440' => 'р',
                        '\u0441' => 'с',
                        '\u0442' => 'т',
                        '\u0443' => 'у',
                        '\u0444' => 'ф',
                        '\u0445' => 'х',
                        '\u0446' => 'ц',
                        '\u0447' => 'ч',
                        '\u0448' => 'ш',
                        '\u0449' => 'щ',
                        '\u044a' => 'ъ',
                        '\u044b' => 'ы',
                        '\u044c' => 'ь',
                        '\u044d' => 'э',
                        '\u044e' => 'ю',
                        '\u044f' => 'я',
                        
                        '\u0401' => 'Ё',
                        '\u0406' => 'І',
                        '\u0407' => 'Ї',
                        '\u0451' => 'ё',
                        '\u0456' => 'і',
                        '\u0457' => 'ї',
                        '\u2116' => '№',
                    ];
                    
                    foreach ($table as $key => $value)
                    {
                    $info = str_replace($key, $value, $info);
                    
                    }
                    $info = str_replace('город '.self::BASE_CT_RU, '', $info);
                    $info = str_replace('пгт '.self::BASE_CT_RU, '', $info);
                    $info = str_replace('\"', '"', $info);
                    $info = str_replace('(', ' (', $info);
                    
                    $infoArr = explode(',', $info, 2);
                    
                    $this->writeToCSV($infoArr);
                }
                        
            }
            
        }
        
    }
    
    public function writeToCSV($infoArr)
    {
        
        $fp = fopen(self::BASE_FILENAME, 'a');
	        
        fputcsv($fp, $infoArr);
	fclose($fp);      
        
    }    

}


