<!DOCTYPE html>

<html>

<head>
  <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"> </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script type="text/javascript" src="main.js"> </script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="mystyle.css">
  <title>Home</title>
</head>

<body>
  <!-- querying the database to pull data-->
    <!-- game canvas -->
  <canvas id=pane style="display:none"></canvas>
  <input id="inputtext" type="text" style="z-index:101; position:absolute; top:90%; left:30%;width:40%;display:none;" class="form-control" />

  <!-- module version new -->
  <script type="module">
    import {
      Game,
      restartLevel,
      endgameDisplayLayout,
      Booster,
      Enemy
    } from "./gameNew.js"
    const allLevels = document.getElementsByClassName("regular-levels")
    for (let i = 1; i < allLevels.length; i++) {
      let mini = i
      let maxi = i
      allLevels[i - 1].addEventListener('click', function() {
        new Game(mini, maxi)
      })
    }
  </script>
  <script>
    import("./gameNew.js")
    // custom game start
    let formGame = document.getElementById("customGameForm")
    formGame.addEventListener("click", function() {
      const startingLevelElement = formGame.elements["minimumLevel"]
      const endingLevelElement = formGame.elements["maximumLevel"]

      let startingLevelForGame = startingLevelElement.value
      let endingLevelForGame = endingLevelElement.value

      if (startingLevelForGame > endingLevelForGame) {
        new Game(startingLevelForGame, startingLevelForGame)
      } else {
        new Game(startingLevelForGame, endingLevelForGame)
      }
    })
  </script>

  <!-- old versions
  <script>
    const allLevels = document.getElementsByClassName("regular-levels")
    for (let i = 1; i < allLevels.length; i++) {
      let mini = 25 * (i - 1)
      let maxi = 25 + 25 * (i - 1)
      allLevels[i - 1].addEventListener('click', function() {
        const game = new game(mini, maxi)
      })
    }
  </script>

  <script src="game0.js">

  </script> -->

  <!-- game menu/level select -->
  <div class="container-fluid" id="gameMenu" style="display:block; position:absolute; top:30%; left:0px;">
    <div class="row">
      <div class="col"></div>
      <div class="col-lg-8 col-centered"><img width=340px height=85px id='levelselectbtn' onmouseover="imagechange(this.id)" onmouseout="imagechangeback(this.id)" onclick="showLevels(); setButtonColours()" src="/thegame/buttons/levelselectbtn.png"></div>
      <div class="col"></div>
    </div>
    <div class="row">
      <div class="col"></div>
      <div class="col-lg-8 center levels">
        <button class="btn-lg" onclick="game(0,149)">HSK 1 (1-6)</button>
        <button class="btn-lg" onclick="game(150,299)">HSK 2 (7-12)</button>
        <button class="btn-lg" onclick="game(300,599)">HSK 3 (13-24)</button>
        <button class="btn-lg" onclick="game(600,1199)">HSK 4 (25-48)</button>
        <button class="btn-lg" onclick="game(1200,2500)">HSK 5 (48-100)</button>
      </div>
      <div class="col"></div>
    </div>
    <div class="row">
      <div class="col"></div>
      <div class="col-lg-8 levels button-wrapper">
        <!-- making all the levels -->
        <button id='levelBtn1' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>1</button><button id='levelBtn2' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>2</button><button id='levelBtn3' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>3</button><button id='levelBtn4' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>4</button><button id='levelBtn5' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>5</button><button id='levelBtn6' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>6</button><button id='levelBtn7' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>7</button><button id='levelBtn8' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>8</button><button id='levelBtn9' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>9</button><button id='levelBtn10' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>10</button><button id='levelBtn11' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>11</button><button id='levelBtn12' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>12</button><button id='levelBtn13' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>13</button><button id='levelBtn14' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>14</button><button id='levelBtn15' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>15</button><button id='levelBtn16' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>16</button><button id='levelBtn17' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>17</button><button id='levelBtn18' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>18</button><button id='levelBtn19' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>19</button><button id='levelBtn20' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>20</button><button id='levelBtn21' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>21</button><button id='levelBtn22' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>22</button><button id='levelBtn23' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>23</button><button id='levelBtn24' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>24</button><button id='levelBtn25' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>25</button><button id='levelBtn26' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>26</button><button id='levelBtn27' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>27</button><button id='levelBtn28' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>28</button><button id='levelBtn29' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>29</button><button id='levelBtn30' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>30</button><button id='levelBtn31' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>31</button><button id='levelBtn32' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>32</button><button id='levelBtn33' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>33</button><button id='levelBtn34' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>34</button><button id='levelBtn35' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>35</button><button id='levelBtn36' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>36</button><button id='levelBtn37' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>37</button><button id='levelBtn38' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>38</button><button id='levelBtn39' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>39</button><button id='levelBtn40' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>40</button><button id='levelBtn41' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>41</button><button id='levelBtn42' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>42</button><button id='levelBtn43' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>43</button><button id='levelBtn44' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>44</button><button id='levelBtn45' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>45</button><button id='levelBtn46' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>46</button><button id='levelBtn47' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>47</button><button id='levelBtn48' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>48</button><button id='levelBtn49' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>49</button><button id='levelBtn50' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>50</button><button id='levelBtn51' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>51</button><button id='levelBtn52' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>52</button><button id='levelBtn53' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>53</button><button id='levelBtn54' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>54</button><button id='levelBtn55' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>55</button><button id='levelBtn56' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>56</button><button id='levelBtn57' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>57</button><button id='levelBtn58' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>58</button><button id='levelBtn59' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>59</button><button id='levelBtn60' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>60</button><button id='levelBtn61' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>61</button><button id='levelBtn62' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>62</button><button id='levelBtn63' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>63</button><button id='levelBtn64' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>64</button><button id='levelBtn65' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>65</button><button id='levelBtn66' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>66</button><button id='levelBtn67' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>67</button><button id='levelBtn68' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>68</button><button id='levelBtn69' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>69</button><button id='levelBtn70' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>70</button><button id='levelBtn71' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>71</button><button id='levelBtn72' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>72</button><button id='levelBtn73' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>73</button><button id='levelBtn74' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>74</button><button id='levelBtn75' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>75</button><button id='levelBtn76' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>76</button><button id='levelBtn77' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>77</button><button id='levelBtn78' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>78</button><button id='levelBtn79' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>79</button><button id='levelBtn80' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>80</button><button id='levelBtn81' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>81</button><button id='levelBtn82' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>82</button><button id='levelBtn83' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>83</button><button id='levelBtn84' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>84</button><button id='levelBtn85' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>85</button><button id='levelBtn86' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>86</button><button id='levelBtn87' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>87</button><button id='levelBtn88' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>88</button><button id='levelBtn89' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>89</button><button id='levelBtn90' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>90</button><button id='levelBtn91' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>91</button><button id='levelBtn92' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>92</button><button id='levelBtn93' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>93</button><button id='levelBtn94' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>94</button><button id='levelBtn95' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>95</button><button id='levelBtn96' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>96</button><button id='levelBtn97' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>97</button><button id='levelBtn98' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>98</button><button id='levelBtn99' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>99</button><button id='levelBtn100' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>100</button>      </div>
      <div class="col"></div>
    </div>
    <div class="row">
      <div class="col"></div>
      <div class="col-lg-8 col-centered"><img width=340px height=85px id='customgamebtn' onmouseover="imagechange(this.id)" onmouseout="imagechangeback(this.id)" onclick="showCustom()" src="/thegame/buttons/customgamebtn.png"></div>
      <div class="col"></div>
    </div>
    <div class="row">
      <div class="col"></div>
      <div id="customGame" class="col-lg-8 col-centered" style="display:none">
        <h3>Make a Custom Game (between 1 and 100):</h3>
        <form method="POST" id="customGameForm">
          <div style="height:10px"></div>
          <div class="form-group">
            <label for="startLevel">Start level: </label>
            <select name="minimumLevel">
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                              <option value="7">7</option>
                              <option value="8">8</option>
                              <option value="9">9</option>
                              <option value="10">10</option>
                              <option value="11">11</option>
                              <option value="12">12</option>
                              <option value="13">13</option>
                              <option value="14">14</option>
                              <option value="15">15</option>
                              <option value="16">16</option>
                              <option value="17">17</option>
                              <option value="18">18</option>
                              <option value="19">19</option>
                              <option value="20">20</option>
                              <option value="21">21</option>
                              <option value="22">22</option>
                              <option value="23">23</option>
                              <option value="24">24</option>
                              <option value="25">25</option>
                              <option value="26">26</option>
                              <option value="27">27</option>
                              <option value="28">28</option>
                              <option value="29">29</option>
                              <option value="30">30</option>
                              <option value="31">31</option>
                              <option value="32">32</option>
                              <option value="33">33</option>
                              <option value="34">34</option>
                              <option value="35">35</option>
                              <option value="36">36</option>
                              <option value="37">37</option>
                              <option value="38">38</option>
                              <option value="39">39</option>
                              <option value="40">40</option>
                              <option value="41">41</option>
                              <option value="42">42</option>
                              <option value="43">43</option>
                              <option value="44">44</option>
                              <option value="45">45</option>
                              <option value="46">46</option>
                              <option value="47">47</option>
                              <option value="48">48</option>
                              <option value="49">49</option>
                              <option value="50">50</option>
                              <option value="51">51</option>
                              <option value="52">52</option>
                              <option value="53">53</option>
                              <option value="54">54</option>
                              <option value="55">55</option>
                              <option value="56">56</option>
                              <option value="57">57</option>
                              <option value="58">58</option>
                              <option value="59">59</option>
                              <option value="60">60</option>
                              <option value="61">61</option>
                              <option value="62">62</option>
                              <option value="63">63</option>
                              <option value="64">64</option>
                              <option value="65">65</option>
                              <option value="66">66</option>
                              <option value="67">67</option>
                              <option value="68">68</option>
                              <option value="69">69</option>
                              <option value="70">70</option>
                              <option value="71">71</option>
                              <option value="72">72</option>
                              <option value="73">73</option>
                              <option value="74">74</option>
                              <option value="75">75</option>
                              <option value="76">76</option>
                              <option value="77">77</option>
                              <option value="78">78</option>
                              <option value="79">79</option>
                              <option value="80">80</option>
                              <option value="81">81</option>
                              <option value="82">82</option>
                              <option value="83">83</option>
                              <option value="84">84</option>
                              <option value="85">85</option>
                              <option value="86">86</option>
                              <option value="87">87</option>
                              <option value="88">88</option>
                              <option value="89">89</option>
                              <option value="90">90</option>
                              <option value="91">91</option>
                              <option value="92">92</option>
                              <option value="93">93</option>
                              <option value="94">94</option>
                              <option value="95">95</option>
                              <option value="96">96</option>
                              <option value="97">97</option>
                              <option value="98">98</option>
                              <option value="99">99</option>
                              <option value="100">100</option>
                          </select>
            <label for="startLevel">End level: </label>
            <select name="maximumLevel">
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                              <option value="7">7</option>
                              <option value="8">8</option>
                              <option value="9">9</option>
                              <option value="10">10</option>
                              <option value="11">11</option>
                              <option value="12">12</option>
                              <option value="13">13</option>
                              <option value="14">14</option>
                              <option value="15">15</option>
                              <option value="16">16</option>
                              <option value="17">17</option>
                              <option value="18">18</option>
                              <option value="19">19</option>
                              <option value="20">20</option>
                              <option value="21">21</option>
                              <option value="22">22</option>
                              <option value="23">23</option>
                              <option value="24">24</option>
                              <option value="25">25</option>
                              <option value="26">26</option>
                              <option value="27">27</option>
                              <option value="28">28</option>
                              <option value="29">29</option>
                              <option value="30">30</option>
                              <option value="31">31</option>
                              <option value="32">32</option>
                              <option value="33">33</option>
                              <option value="34">34</option>
                              <option value="35">35</option>
                              <option value="36">36</option>
                              <option value="37">37</option>
                              <option value="38">38</option>
                              <option value="39">39</option>
                              <option value="40">40</option>
                              <option value="41">41</option>
                              <option value="42">42</option>
                              <option value="43">43</option>
                              <option value="44">44</option>
                              <option value="45">45</option>
                              <option value="46">46</option>
                              <option value="47">47</option>
                              <option value="48">48</option>
                              <option value="49">49</option>
                              <option value="50">50</option>
                              <option value="51">51</option>
                              <option value="52">52</option>
                              <option value="53">53</option>
                              <option value="54">54</option>
                              <option value="55">55</option>
                              <option value="56">56</option>
                              <option value="57">57</option>
                              <option value="58">58</option>
                              <option value="59">59</option>
                              <option value="60">60</option>
                              <option value="61">61</option>
                              <option value="62">62</option>
                              <option value="63">63</option>
                              <option value="64">64</option>
                              <option value="65">65</option>
                              <option value="66">66</option>
                              <option value="67">67</option>
                              <option value="68">68</option>
                              <option value="69">69</option>
                              <option value="70">70</option>
                              <option value="71">71</option>
                              <option value="72">72</option>
                              <option value="73">73</option>
                              <option value="74">74</option>
                              <option value="75">75</option>
                              <option value="76">76</option>
                              <option value="77">77</option>
                              <option value="78">78</option>
                              <option value="79">79</option>
                              <option value="80">80</option>
                              <option value="81">81</option>
                              <option value="82">82</option>
                              <option value="83">83</option>
                              <option value="84">84</option>
                              <option value="85">85</option>
                              <option value="86">86</option>
                              <option value="87">87</option>
                              <option value="88">88</option>
                              <option value="89">89</option>
                              <option value="90">90</option>
                              <option value="91">91</option>
                              <option value="92">92</option>
                              <option value="93">93</option>
                              <option value="94">94</option>
                              <option value="95">95</option>
                              <option value="96">96</option>
                              <option value="97">97</option>
                              <option value="98">98</option>
                              <option value="99">99</option>
                              <option value="100">100</option>
                          </select>
          </div>
          <button class="btn btn-info" id="makeGameBtn" type="submit">Start game</button>

        </form>
      </div>
      <div class="col"></div>
    </div>
    <div class="row">
      <div class="col"></div>
      <div><img width=340px height=85px id='scoreboardbtn' onclick="location.href = '//localhost/thegame/scoreboard.php';" onmouseover="imagechange(this.id)" onmouseout="imagechangeback(this.id)" src="/thegame/buttons/scoreboardbtn.png"></div>
      <div class="col"></div>
    </div>
    <div class="row">
      <div class="col"></div>
      <div><img width=340px height=85px id='statisticsbtn' onclick="modalDisplay()" onmouseover="imagechange(this.id)" onmouseout="imagechangeback(this.id)" src="/thegame/buttons/statisticsbtn.png"></div>
      <div class="col"></div>
    </div>
    <div class="row">
      <div class="col"></div>
      <div><img width=340px height=85px id='settingsbtn' onclick="settingsDisplay()" onmouseover="imagechange(this.id)" onmouseout="imagechangeback(this.id)" src="/thegame/buttons/settingsbtn.png"></div>
      <div class="col"></div>
    </div>
  </div>

  <!-- game settings -->
  <div id="settingsModal" class="text-center-class modal">
    <div class='modal-content' style="width:450px">
      <h1>Game Settings</h1>
      <form action="/thegame/user_settings.php" method="POST">
        <div style="height:10px"></div>
        <div class="form-group">
          <label for="difficultySet"><b>Difficulty</b></label>
          <select id="difficultySetting" class="form-select" name="difficultySetting">
            <option value="Easy">Easy</option>
            <option value="Normal">Normal</option>
            <option value="Hard">Hard</option>
            <option value="Nightmare">Nightmare</option>
          </select> (Current difficulty: Easy)
        </div>
        <div class="form-group">
          <label for="languageSet"><b>Language</b></label>
          <select id="languageSetting" class="form-select" name="languageSetting">
            <option value="Chinese">Chinese</option>
            <option value="Arabic">Arabic</option>
            <option value="Japanese">Japanese</option>
          </select> (Current Language: Chinese)
        </div>
        <div class="form-group">
          <label for="gamemodeSet"><b>Game mode</b></label>
          <select id="gamemodeSetting" class="form-select" name="gamemodeSetting">
            <option value="Regular">Regular</option>
            <option value="Endurance">Endurance</option>
            <option value="Beat The Clock">Beat The Clock</option>
            <option value="Race To Finish">Race To Finish</option>
            <option value="Practice">Practice</option>
          </select> (Current Game mode: Beat The Clock)
        </div>
        <div class="form-group">
          <label for="practiceModal"><b>Difficulty: </b></label>
          <img src="/thegame/buttons/leftarrow.png" style="width:5%; height:width">Easy<img src="/thegame/buttons/rightarrow.png" style="width:5%; height:width">
        </div>
        <button class="btn btn-info" type="submit" id="userSettingBtn">Submit changes</button>
      </form>
    </div>
  </div>
  <!-- scoreboard modal -->
  <div id='statisticsModal' class='modal'>
    <div class='modal-content'>
      <span id='statisticsClose' onclick=''>&times;</span>
      <iframe src=http://localhost/thegame/statistics.php style="height:400px"></iframe>
    </div>
  </div>
  <!-- variables used by homepage.php and main.js -->
  <script type="text/javascript">
    let perfectStates = [1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
    let totalLevelsForGame = 100;
    let levelScores = [1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
  </script>

  <script>
    // colouring buttons
    for (let i = 0; i < totalLevelsForGame; i++)
      if (perfectStates[i] === 1) {
        // document.getElementById('levelBtn'+(i+1)).classList.add('easy-shadow');
        // document.getElementById('levelBtn'+(i+1)).style.borderColor = 'rgba(0,0,0,0)';
      } else if (perfectStates[i] === 2) {
      document.getElementById('levelBtn' + (i + 1)).classList.add('normal-shadow');
      document.getElementById('levelBtn' + (i + 1)).style.borderColor = 'rgba(0,0,0,0)';
    } else if (perfectStates[i] === 3) {
      document.getElementById('levelBtn' + (i + 1)).classList.add('hard-shadow');
      document.getElementById('levelBtn' + (i + 1)).style.borderColor = 'rgba(0,0,0,0)';
    } else if (perfectStates[i] === 4) {
      document.getElementById('levelBtn' + (i + 1)).classList.add('nightmare-shadow');
      document.getElementById('levelBtn' + (i + 1)).style.borderColor = 'rgba(0,0,0,0)';
    }
  </script>
  <!-- canvas/game and input boxes -->
  <div id="boosterImages" style="display:none">
    <img id="slowMoImage" src="/thegame/booster/slowmo.png" style="z-index:101; position:absolute; top:30%; left:1%; display:block; width:6%;height:width; opacity:0.3">
    <img id="freezeImage" src="/thegame/booster/freeze.png" style="z-index:101; position:absolute; top:45%; left:1%; display:block; width:6%;height:width; opacity: 0.3">
    <img id="frenzyImage" src="/thegame/booster/frenzy.png" style="z-index:101; position:absolute; top:60%; left:1%; display:block; width:6%;height:width; opacity: 0.3">
  </div>


  <!-- level introduction -->
  <img id="introductionImage" src="/thegame/buttons/levelselector.png" style="display:none; position:absolute; top:250px; left:600px;">

  <!-- end of game "page"-->
  <div class="container-fluid results" style="display:none; color:white">
    <div class="row">
      <div class="col-lg-10 col-centered"><button id="resultsbtn" onclick="showhideresults()">Toggle</button></div>
    </div>
  </div>
  <div class="container-fluid results" style="display:none; color:white;margin-left:auto;margin-right:auto">
    <div id=failureTableDiv style="display:block">
      <table id=failureTable class="col-centered table-bordered table-striped" style="background-color:rgba(28,78,128,0.8);display:table; color:white; font-size:20pt;text-align:center;"></table>
    </div>
    <div id=successTableDiv style="display:none">
      <table id=successTable class="col-centered table-bordered table-striped" style="background-color:rgba(28,78,128,0.8); display:table; color:white; font-size:20pt; text-align:center;"></table>
    </div>
  </div>

  <!-- pause game interface -->
  <div id="pauseinterface" class="centerpause" style="display:none; position:absolute; top:100px; left:250px;">
    <div><img id="continuebtn" onmouseover="imagechange(this.id)" onmouseout="imagechangeback(this.id)" src="/thegame/buttons/continuebtn.png"> </div>
    <div><img id="restartbtn" onmouseover="imagechange(this.id)" onmouseout="imagechangeback(this.id)" src="/thegame/buttons/restartbtn.png"> </div>
    <div><img id="homebtn" onmouseover="imagechange(this.id)" onmouseout="imagechangeback(this.id)" src="/thegame/buttons/homebtn.png"> </div>
  </div>

</body>

</html>