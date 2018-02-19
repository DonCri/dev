<?

    // Klassendefinition
    class SchwellWerteSWW extends IPSModule {
        /**
        * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
        * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wiefolgt zur Verfügung gestellt:
        *
        * ABC_MeineErsteEigeneFunktion($id);
        *
        */

        // Überschreibt die interne IPS_Create($id) Funktion
        public function Create() {
        // Diese Zeile nicht löschen.
        parent::Create();

        if(!IPS_VariableProfileExists("BRELAG.SollSWW")) {
    			IPS_CreateVariableProfile("BRELAG.SollSWW", 1);
          IPS_SetVariableProfileValues("BRELAG.SollSWW", 0, 2, 0);
    			IPS_SetVariableProfileIcon("BRELAG.SollSWW", "");
    			IPS_SetVariableProfileAssociation("BRELAG.SollSWW", 0, $this->Translate("lower"), "", -1);
    			IPS_SetVariableProfileAssociation("BRELAG.SollSWW", 1, $this->Translate("upper"), "", -1);
    		}

        $this->RegisterVariableString("upperValueSun", "Oberer Schwellwert Sonne", "", "1");
        $this->RegisterVariableString("lowerValueSun", "Unterer Schwellwert Sonne", "", "2");
        $this->RegisterVariableInteger("stateSun", "Aktiver Schwellwert Sonne", "BRELAG.SollSWW", "3");
        $this->RegisterVariableBoolean("StateChangeSun", "Beschattung aktivieren / deaktivieren", "BRELAG.Switch", "0");

        $this->RegisterVariableString("upperValueWind", "Oberer Schwellwert Wind", "", "4");
        $this->RegisterVariableString("lowerValueWind", "Unterer Schwellwert Wind", "", "5");
        $this->RegisterVariableString("stateWind", "Aktiver Schwellwert Wind", "", "6");

        $this->RegisterPropertyInteger("LightValue", 0);
        $this->RegisterPropertyInteger("RainValue", 0);
        $this->RegisterPropertyString("upperEventSun", "");
        $this->RegisterPropertyString("lowerEventSun", "");
        $this->EnableAction("upperValueSun");
        $this->EnableAction("lowerValueSun");
        $this->EnableAction("StateChangeSun");

        $this->RegisterPropertyInteger("WindValue", 0);
        $this->EnableAction("upperValueWind");
        $this->EnableAction("lowerValueWind");

      }

      public function RequestAction($Ident, $Value) {

            switch($Ident) {
                  case "upperValueSun":
                  //Neuen Wert in die Statusvariable schreiben
                    SetValue($this->GetIDForIdent($Ident), $Value);
                  break;
                  case "lowerValueSun":
                    //Neuen Wert in die Statusvariable schreiben
                    SetValue($this->GetIDForIdent($Ident), $Value);
                  break;
                  case "upperValueWind":
                    //Neuen Wert in die Statusvariable schreiben
                      SetValue($this->GetIDForIdent($Ident), $Value);
                  break;
                  case "lowerValueWind":
                      //Neuen Wert in die Statusvariable schreiben
                      SetValue($this->GetIDForIdent($Ident), $Value);
                  break;
                  case "StateChangeSun":
                      //Neuen Wert in die Statusvariable schreiben
                      SetValue($this->GetIDForIdent($Ident), $Value);
                      $this->BeschattungAktivDeaktiv();
                  break;
                  }

    }

      public function Beschattung() {

        $Lichtsensor = GetValue($this->ReadPropertyInteger("LightValue"));
        $Regensensor = GetValue($this->ReadPropertyInteger("RainValue"));
        $oberenSchwellwert = GetValue($this->GetIDForIdent("upperValueSun"));
        $unterenSchwellwert = GetValue($this->GetIDForIdent("lowerValueSun"));
        $Status = GetValue($this->GetIDForIdent("stateSun"));

        if($Status <> "oben")
          {
            if($Lichtsensor >= $oberenSchwellwert && $Regensensor == false)
            {
              SetValue($this->GetIDForIdent("stateSun"), "1");
            }
          } elseif($Status <> "unten")
              {
                if($Lichtsensor <= $unterenSchwellwert)
                  {
                    SetValue($this->GetIDForIdent("stateSun"), "0");
                  }
                }

        }

        public function BeschattungAktivDeaktiv(){
          $state = GetValue($this->GetIDForIdent("StateChangeSun"));
          switch ($state) {
            case true:
                IPS_SetEventActive($this->ReadPropertyString("upperEventSun"), true);
                IPS_SetEventActive($this->ReadPropertyString("lowerEventSun"), true);
              break;
            case false:
              IPS_SetEventActive($this->ReadPropertyString("upperEventSun"), false);
              IPS_SetEventActive($this->ReadPropertyString("lowerEventSun"), false);
            break;
          }
        }

        public function Wind() {

          $Windsensor = GetValue($this->ReadPropertyInteger("WindValue"));
          $oberenSchwellwert = GetValue($this->GetIDForIdent("upperValueWind"));
          $unterenSchwellwert = GetValue($this->GetIDForIdent("lowerValueWind"));
          $Status = GetValue($this->GetIDForIdent("stateWind"));

          if($Status <> "oben")
            {
              if($Windsensor >= $oberenSchwellwert)
              {
                SetValue($this->GetIDForIdent("stateWind"), "oben");
              }
            } elseif($Status <> "unten")
                {
                  if($Windsensor <= $unterenSchwellwert)
                    {
                      SetValue($this->GetIDForIdent("stateWind"), "unten");
                    }
                  }
          }

}



?>
