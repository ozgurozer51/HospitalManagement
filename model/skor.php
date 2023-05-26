<script language="javascript">
    function CalcPAS(form) {
        form.zpas.value = form.pas[form.pas.selectedIndex].value
        form.zapa.value = CalcAPA(form)
        form.zmorta.value = CalcMorta(form)
        form.zmort.value = CalcMort(form)
    }

    function CalcPAD(form) {
        form.zpad.value = form.pad[form.pad.selectedIndex].value
        form.zapa.value = CalcAPA(form)
        form.zmorta.value = CalcMorta(form)
        form.zmort.value = CalcMort(form)
    }

    function CalcFC(form) {
        form.zfc.value = form.fc[form.fc.selectedIndex].value
        form.zapa.value = CalcAPA(form)
        form.zmorta.value = CalcMorta(form)
        form.zmort.value = CalcMort(form)
    }

    function CalcFR(form) {
        form.zfr.value = form.fr[form.fr.selectedIndex].value
        form.zapa.value = CalcAPA(form)
        form.zmorta.value = CalcMorta(form)
        form.zmort.value = CalcMort(form)
    }

    function CalcPAO(form) {
        form.zpao.value = form.pao[form.pao.selectedIndex].value
        form.zapa.value = CalcAPA(form)
        form.zmorta.value = CalcMorta(form)
        form.zmort.value = CalcMort(form)
    }

    function CalcPCO(form) {
        form.zpco.value = form.pco[form.pco.selectedIndex].value
        form.zapa.value = CalcAPA(form)
        form.zmorta.value = CalcMorta(form)
        form.zmort.value = CalcMort(form)
    }

    function CalcPT(form) {
        form.zpt.value = form.pt[form.pt.selectedIndex].value
        form.zapa.value = CalcAPA(form)
        form.zmorta.value = CalcMorta(form)
        form.zmort.value = CalcMort(form)
    }

    function CalcKA(form) {
        form.zka.value = form.ka[form.ka.selectedIndex].value
        form.zapa.value = CalcAPA(form)
        form.zmorta.value = CalcMorta(form)
        form.zmort.value = CalcMort(form)
    }

    function CalcGLAS(form) {
        form.zglas.value = form.glas[form.glas.selectedIndex].value
        form.zapa.value = CalcAPA(form)
        form.zmorta.value = CalcMorta(form)
        form.zmort.value = CalcMort(form)
    }

    function CalcBILI(form) {
        form.zbili.value = form.bili[form.bili.selectedIndex].value
        form.zapa.value = CalcAPA(form)
        form.zmorta.value = CalcMorta(form)
        form.zmort.value = CalcMort(form)
    }

    function CalcGLY(form) {
        form.zgly.value = form.gly[form.gly.selectedIndex].value
        form.zapa.value = CalcAPA(form)
        form.zmorta.value = CalcMorta(form)
        form.zmort.value = CalcMort(form)
    }

    function CalcHCO(form) {
        form.zhco.value = form.hco[form.hco.selectedIndex].value
        form.zapa.value = CalcAPA(form)
        form.zmorta.value = CalcMorta(form)
        form.zmort.value = CalcMort(form)
    }

    function CalcPUP(form) {
        form.zpup.value = form.pup[form.pup.selectedIndex].value
        form.zapa.value = CalcAPA(form)
        form.zmorta.value = CalcMorta(form)
        form.zmort.value = CalcMort(form)
    }

    function CalcCA(form) {
        form.zca.value = form.ca[form.ca.selectedIndex].value
        form.zapa.value = CalcAPA(form)
        form.zmorta.value = CalcMorta(form)
        form.zmort.value = CalcMort(form)
    }


    function CalcAPA(form) {
        z = eval(form.zpas.value)
        z = z + eval(form.zpad.value)
        z = z + eval(form.zfc.value)
        z = z + eval(form.zfr.value)
        z = z + eval(form.zpao.value)
        z = z + eval(form.zpco.value)
        z = z + eval(form.zpt.value)
        z = z + eval(form.zka.value)
        z = z + eval(form.zglas.value)
        z = z + eval(form.zbili.value)
        z = z + eval(form.zgly.value)
        z = z + eval(form.zhco.value)
        z = z + eval(form.zpup.value)
        z = z + eval(form.zca.value)
        return '' + z
    }

    function CalcMorta(form) {
        z = eval(form.zapa.value)
        z = (0.207 * z) - 4.782
        z = eval(form.y.value * "-0.005" + "+z")
        if (form.y.value > 228) {
            alert("upper limit for age used in implementation will be 19th birthday")
        }
        z = Math.exp(z) / (1 + Math.exp(z))
        z = Fmt(100 * z) + " %"
        form.zmorta.value = z
        return z
    }

    function CalcMort(form) {
        z = eval(form.zapa.value)
        z = (0.207 * z) - 4.782
        z = eval(form.y.value * "-0.005" + "+z")
        z = z - 0.433
        z = Math.exp(z) / (1 + Math.exp(z))
        z = Fmt(100 * z) + " %"
        form.zmort.value = z
        return z
    }

    function Fmt(x) {
        var v
        if (x >= 0) {
            v = '' + (x + 0.05)
        } else {
            v = '' + (x - 0.05)
        }
        return v.substring(0, v.indexOf('.') + 2)
    }

    function erreur(text) {
        $.jGrowl(text, {position: 'center'});
    }

    function clearChamp(id, valeur) {
        if (document.getElementById(id).value == valeur)
            document.getElementById(id).value = '';
        else if (!document.getElementById(id).value)
            document.getElementById(id).value = valeur;
    }

    function clearChamp2(id, valeur) {
        if (document.getElementById(id).value == valeur) {
            document.getElementById(id).value = '';
            //document.getElementById(id).type = 'password';
        } else if (!document.getElementById(id).value) {
            document.getElementById(id).value = valeur;
            //document.getElementById(id).type = 'text';
        }
    }
</script>

<form method="POST">

    <div class="row">
        <div class="col-3">
            Sistolik Kan Basıncı (mmHg)
        </div>
        <div class="col-3">
            <select class="form-select" name="pas" onchange="CalcPAS(this.form)">
                <option value="0" selected=""></option>
                <option value="0">(Yenidoğan: 0-1 year)</option>
                <option value="6">Yenidoğan &gt; 160</option>
                <option value="2">Yenidoğan 130-160</option>
                <option value="0">Yenidoğan 66-129</option>
                <option value="2">Yenidoğan 55-65</option>
                <option value="6">Yenidoğan 40-54</option>
                <option value="7">Yenidoğan &lt; 40</option>
                <option value="0">..................</option>
                <option value="6">Çocuk &gt; 200</option>
                <option value="2">Çocuk 150-200</option>
                <option value="0">Çocuk 76-149</option>
                <option value="2">Çocuk 65-75</option>
                <option value="6">Çocuk 50-64</option>
                <option value="7">Çocuk &lt; 50</option>
            </select>
            <input class="form-control" type="text" name="zpas" value="0">
        </div>

        <div class="col-3">
            <label class="from-label col-3">Diastolik Kan Basıncı (mmHg)</label>
        </div>

        <div class="col-3">
            <select class="form-select" name="pad" onchange="CalcPAD(this.form)">
                <option value="0" selected="">
                </option>
                <option value="0">(Tüm Yaşlar)
                </option>
                <option value="6">&gt; 110
                </option>
            </select>
            <input class="form-control" type="text" name="zpad" value="0">
        </div>

    </div>

    <div class="row">
        <div class="col-3">
            <label class="form-label"> Kalp Hızı (beats/ min) </label>
        </div>

        <div class="col-3">
            <select class="form-select" name="fc" onchange="CalcFC(this.form)">
                <option value="0" selected="">
                </option>
                <option value="4">Yenidoğan &gt; 160
                </option>
                <option value="0">Yenidoğan 91 - 159
                </option>
                <option value="4">Yenidoğan&lt; 90
                </option>
                <option value="0">..................
                </option>
                <option value="4">Çocuk &gt; 150
                </option>
                <option value="0">Çocuk 81 - 149
                </option>
                <option value="4">Çocuk &lt; 80
                </option>
            </select>
            <input class="form-control" type="text" name="zfc" value="0">
        </div>

        <div class="col-3">
            <label class="form-label">
                Solunum Sayısı (breaths/ min)
            </label>
        </div>

        <div class="col-3">
            <select class="form-select" name="fr" onchange="CalcFR(this.form)">
                <option value="0" selected="">
                </option>
                <option value="1">Yenidoğan 61-90
                </option>
                <option value="5">Yenidoğan &gt; 90
                </option>
                <option value="5">Yenidoğan : apnea
                </option>
                <option value="0">..................
                </option>
                <option value="1">Çocuk 51-70
                </option>
                <option value="5">Çocuk &gt; 70
                </option>
                <option value="5">Çocuk : apnea
                </option>
            </select>

            <input class="form-control" type="text" name="zfr" value="0">
        </div>
    </div>

    <div class="row">

        <div class="col-3">
            <div class="noir12">
                <label class="form-label">Pa O2 / FI O2 (mmHg)</label>
            </div>
        </div>

        <div class="col-3">
            <div class="noir12">
                <select class="form-select" name="pao" onchange="CalcPAO(this.form)">
                    <option value="0" selected="">
                    </option>
                    <option value="2">200-300
                    </option>
                    <option value="3">&lt; 200
                    </option>
                </select>
            </div>
            <input class="form-control" type="text" name="zpao" value="0">
        </div>


        <div class="col-3">
            <div class="noir12">Pa CO2 (mmHg)</div>
        </div>
        <div class="col-3">
            <select class="form-select" name="pco" onchange="CalcPCO(this.form)">
                <option value="0" selected=""></option>
                <option value="1">51-65</option>
                <option value="5">&gt; 65</option>
            </select>
        </div>
        <input class="form-control" type="text" name="zpco" value="0">

    </div>


    <div class="row">
        <div class="co-3">
            <div class="noir12"><label class="form-label">PT / PTT</label></div>
        </div>

        <div class="col-3">
            <select class="form-select" name="pt" onchange="CalcPT(this.form)">
                <option value="0" selected="">
                </option>
                <option value="2">&gt; 1,5 Zaman Kontrollü
                </option>
            </select>
            <input class="form-control" type="text" name="zpt" value="0">
        </div>


        <div class="col-3">
            <label class="form-label">
                Total Bilirubin
            </label>
        </div>

        <div class="col-3">
            <select class="form-select" name="bili" onchange="CalcBILI(this.form)">
                <option value="0" selected="">
                </option>
                <option value="0">(if &gt; 1 ay)
                </option>
                <option value="6">&gt; 3.5 mg/dL
                </option>
                <option value="6">&gt; 60 micromol/L
                </option>
            </select>
            <input class="form-control" type="text" name="zbili" value="0">
        </div>
    </div>


    <div class="row">
        <div class="col-3">
            <div class="noir12"><label class="form-label">Kalsiyum</label></div>
        </div>

        <div class="col-3">
            <select class="form-select" name="ca" onchange="CalcCA(this.form)">
                <option value="0" selected="">
                </option>
                <option value="6">&lt; 7.0 (mg/dL)
                </option>
                <option value="2">7.0 - 8.0 (mg/dL)
                </option>
                <option value="2">12.0 - 15.0 (mg/dL)
                </option>
                <option value="6">&gt; 15.0 (mg/dL)
                </option>
                <option value="0">..................
                </option>
                <option value="6">&lt; 1,75 mmol/L
                </option>
                <option value="2">1,75-2 mmol/L
                </option>
                <option value="2">3-3,75 mmol/L
                </option>
                <option value="6">&gt;3,75 mmol/L
                </option>
            </select>
            <input class="form-control" type="text" name="zca" value="0">
        </div>
        <div class="col-3">
            <label class="form-label">Potasyum (mEq/L)</label>
        </div>

        <select class="form-select" name="ka" onchange="CalcKA(this.form)">
            <option value="0" selected="">
            </option>
            <option value="5">&lt;3
            </option>
            <option value="1">3-3,5
            </option>
            <option value="1">6,5-7,5
            </option>
            <option value="5">&gt; 7,5
            </option>
        </select>
        <input class="form-control" type="text" name="zka" value="0">
    </div>

    <div class="row">
        <div class="col-3">
            <label class="form-label">Glukoz</label>
        </div>
        <div class="col-3">
            <select class="form-select" name="gly" onchange="CalcGLY(this.form)">
                <option value="0" selected="">
                </option>
                <option value="8">&lt; 40 (mg/dL)
                </option>
                <option value="4">40 - 60 (mg/dL)
                </option>
                <option value="4">250 - 400 (mg/dL)
                </option>
                <option value="8">&gt; 400 (mg/dL)
                </option>
                <option value="0">..................
                </option>
                <option value="8">&lt; 2,22 mmol/L
                </option>
                <option value="4">2,22-3,33 mmol/L
                </option>
                <option value="4">12,5-22,2 mmol/L
                </option>
                <option value="8">&gt; 22,2 mmol/L
                </option>
            </select>
            <input class="form-control" type="text" name="zgly" value="0">
        </div>


        <div class="col-3">
            <label class="form-label">HCO3<sup>-</sup>(mEq/L)</label>
        </div>
        <div class="col-3">
            <select class="form-select" name="hco" onchange="CalcHCO(this.form)">
                <option value="0" selected="">
                </option>
                <option value="3">&lt; 16
                </option>
                <option value="3">&gt; 32
                </option>
            </select>
            <input class="form-control" type="text" name="zhco" value="0">
        </div>
    </div>

    <div class="row">
        <div class="col-3">
            <label class="form-label">Pupil Reaksiyonu</label>
        </div>

        <div class="col-3">
            <select class="form-select" name="pup" onchange="CalcPUP(this.form)">
                <option value="0" selected="">
                </option>
                <option value="4">eşit değil veya dilate
                </option>
                <option value="10">fikse ve dilate
                </option>
            </select>
            <input class="form-control" type="text" name="zpup" value="0">
        </div>


        <div class="col-3">
            <label class="form-label">PRISM</label>
        </div>

        <div class="col-3">
            <input class="form-control" type="text" name="zapa" value="0" size="4">
        </div>
    </div>

    <input class="form-control" type="reset" value="Temizle" class="btn-rouge">


    Glasgow(<span class="normal"><a href="#glasgow">Yardım</a><a href="#glasgow">)</a>
          

            <select class="form-select" name="glas" onchange="CalcGLAS(this.form)">
              <option value="0" selected="">
              </option><option value="6">&lt; 8
            </option>
            </select>



            <input class="form-control" type="text" name="zglas" value="0">


                                  (Yoğun bakıma yatıştan itibaren ilk 24 saat içerisindeki veriler toplanmalıdır.)

                                              <p align="left">Beklenen Ölüm Oranı
              <input class="form-control" type="text" name="zmorta" value="0" size="7">
          

                                              <p align="left">Postoperatif (kardiyak cerrahi hariç)
              Beklenen Ölüm Oranı
              <input class="form-control" type="text" name="zmort" value="0" size="7">
          

                                              &nbsp;
                    <label class="form-label"> Kaç Aylık=</label>
              <input class="form-control" type="text" name="y" value="0">
          <input class="form-control" type="button" name="Bouton" value="Hesapla"
                 onclick="CalcMorta(form); CalcMort(form)" class="btn-bleu2">


                                              Logit =(0,207*PRISM-(0,005*(ay olarak yaş))-0,433*1(postoperatif ise)-4,782Beklenen Ölüm Oranı=e<sup>logit</sup>/ (1+e<sup>logit</sup>)




              <a name="glasgow"></a>
                    
              <script language="JavaScript">
                  num1 = 0;
                  num2 = 0;
                  num3 = 0;


                  function calcul() {
                      chaine = " ";
                      num = num1 + num2 + num3;
                      document.glasgow.res.value = num;
                  }
              </script>


</form>

