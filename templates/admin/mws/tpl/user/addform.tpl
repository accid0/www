<div class="mws-form-row">
      <label><?= $this->lang[(string)$this->form->логин]; ?></label>
      <div class="mws-form-item large">
        <input rel='1' type="text" name="логин" class="mws-textinput required" />
      </div>
    </div>
    <div class="mws-form-row">
      <label><?= $this->lang[(string)$this->form->имя]; ?></label>
      <div class="mws-form-item large">
        <input type="text" name="имя" class="mws-textinput required" />
      </div>
    </div>
    <div class="mws-form-row">
      <label><?= $this->lang[(string)$this->form->фамилия]; ?></label>
      <div class="mws-form-item large">
        <input type="text" name="фамилия" class="mws-textinput required" />
      </div>
    </div>
    <div class="mws-form-row">
      <label><?= $this->lang[(string)$this->form->интересы]; ?></label>
      <div class="mws-form-item">
        <textarea class="elrte" name='интересы' cols="auto" rows="5"></textarea>
      </div>
    </div>
    <div class="mws-form-row">
      <label><?= $this->lang[(string)$this->form->рождение]; ?></label>
      <div class="mws-form-item">
        <input type='text' readonly="readonly" class="mws-textinput mws-datepicker-wk required date" name='рождение' />
      </div>
    </div>
    <div class="mws-form-row">
    <label><?= $this->lang[(string)$this->form->почта]; ?></label>
    <div class="mws-form-item large">
      <input type="text" name="почта" class="mws-textinput required email" />
    </div>
    </div>
    <div class="mws-form-row">
      <label><?= $this->lang[(string)$this->form->показыватьИмейл]; ?></label>
      <div class="mws-form-item large">
        <ul class="mws-form-list">
          <li><input id="email_yes" type="radio" name="показыватьИмейл" class="required" /> <label for="email_yes">да</label></li>
          <li><input id="email_no" type="radio" name="показыватьИмейл" /> <label for="email_no">нет</label></li>
        </ul>
        <label for="gender" class="error plain" generated="true" style="display:none"></label>
      </div>
    </div>
    <div class="mws-form-row">
      <label><?= $this->lang[(string)$this->form->www]; ?></label>
      <div class="mws-form-item large">
        <input type="text" name="www" class="mws-textinput required url" />
      </div>
    </div>
    <div class="mws-form-row">
      <label><?= $this->lang[(string)$this->form->рождение]; ?></label>
      <div class="mws-form-item large">
        <input rel='0' type="text" name="рождение" class="mws-textinput required digits" />
      </div>
    </div>
    <div class="mws-form-row">
      <label><?= $this->lang[(string)$this->form->роли]; ?></label>
      <div class="mws-form-item small">
        <select rel='2' name= 'роли[]' multiple="multiple" size="2" class="chzn-select required">
          <option>Администраторы</option>
          <option>Гости</option>
        </select>
        <label for="роли[]" class="error" generated="true" style="display:none"></label>
      </div>
    </div>
    <div class="mws-form-row">
      <label>File Input Validation</label>
      <div class="mws-form-item large">
        <input type="file" name="picture" class="required" />
        <label for="picture" class="error" generated="true" style="display:none"></label>
      </div>
    </div>
    <div class="mws-form-row">
      <label>Spinner Validation</label>
      <div class="mws-form-item medium">
        <input type="text" id="s4" name="spinner" class="mws-textinput required" value="10" />
        <label for="s4" class="error" generated="true" style="display:none"></label>
      </div>
    </div>
    <div class="mws-form-row">
      <label>Radiobutton Validation</label>
      <div class="mws-form-item large">
        <ul class="mws-form-list">
          <li><input id="gender_male" type="radio" name="gender" class="required" /> <label for="gender_male">Male</label></li>
          <li><input id="gender_female" type="radio" name="gender" /> <label for="gender_female">Female</label></li>
        </ul>
        <label for="gender" class="error plain" generated="true" style="display:none"></label>
      </div>
    </div>