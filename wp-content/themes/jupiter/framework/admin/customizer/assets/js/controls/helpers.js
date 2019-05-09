/**
 * The function for control visibility condition
 * 
 * @param string controlID  The ID of the control.
 * @param array  conditions List of all conditions.
 * @param string relation   General conditions relation.
 *
 * @since 5.9.4
 */
function mkControlVisibilityCondition(controlID, conditions, relation) {
  wp.customize.control(controlID, function(control) {
    var setVisibility = function() {
      var temp = true;

      switch (relation) {
        case 'OR':
          temp = false;

          // We assume that every conditions are false from the start.
          // Once we met at least one condition that is true, show the field.
          for (var i = 0; i < conditions.length; i++) {
            wp.customize(conditions[i].setting, function(setting) {
              if (temp == false && setting.get() == conditions[i].value) {
                temp = true;
                i = conditions.length;
              }
            });
          }

          break;

        case 'AND':
          temp = true;

          // We assume that every conditions are true from the start.
          // Once we met at least one condition that is false, hide the field.
          for (var i = 0; i < conditions.length; i++) {
            wp.customize(conditions[i].setting, function(setting) {
              if (temp == true && setting.get() != conditions[i].value) {
                temp = false;
                i = conditions.length;
              }
            });
          }

          break;
      }

      return temp;
    };

    var setActiveState = function() {
      control.active.set(setVisibility());
    };

    conditions.forEach(function(condition) {
      wp.customize(condition.setting, function(setting) {
        setting.bind(setActiveState);
      });
    });

    control.active.validate = setVisibility;
    control.active.set(setVisibility());
  });
}
