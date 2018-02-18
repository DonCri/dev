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

        $this->RegisterVariableString("upperValueSun", "Oberer Schwellwert Sonne", "", "1");
        $this->RegisterVariableString("lowerValueSun", "Unterer Schwellwert Sonne", "", "2");
        $this->RegisterVariableString("stateSun", "Aktiver Schwellwert Sonne", "", "3");
        $this->RegisterVariableBoolean("StateChangeSun", "Beschattung aktivieren / deaktivieren", "BRELAG.Switch", "0");

        $this->RegisterVariableString("upperValueWind", "Oberer Schwellwert Wind", "", "4");
        $this->RegisterVariableString("lowerValueWind", "Unterer Schwellwert Wind", "", "5");
        $this->RegisterVariableString("stateWind", "Aktiver Schwellwert Wind", "", "6");


        $this->RegisterPropertyInteger("LightValue", 0);
        $this->RegisterPropertyInteger("RainValue", 0);
        $this->RegisterPropertyString("upperEventSun", 0);
        $this->RegisterPropertyString("lowerEventSun", 0);
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
                      if($this->GetIDForIdent("StateChangeSun") == true) {
                        IPS_SetEventActive($this->ReadPropertyString("upperEventSun"), true);
                        IPS_SetEventActive($this->ReadPropertyString("lowerEventSun"), true);
                      } elseif($this->GetIDForIdent("StateChangeSun") == false) {
                                IPS_SetEventAcitve($this->ReadPropertyString("upperEventSun"), false);
                                IPS_SetEventAcitve($this->ReadPropertyString("lowerEventSun"), false);
                              }
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
              SetValue($this->GetIDForIdent("stateSun"), "oben");
            }
          } elseif($Status <> "unten")
                      {

                        if($Lichtsensor <= $unterenSchwellwert)
                        {
                          SetValue($this->GetIDForIdent("stateSun"), "unten");
                        }
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
