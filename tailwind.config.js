module.exports = {
   
   presets: [
       
       require('./vendor/wireui/wireui/tailwind.config.js')
   ],
   content: [
       
       './vendor/wireui/wireui/resources/**/*.blade.php',
       './vendor/wireui/wireui/ts/**/*.ts',
       './vendor/wireui/wireui/src/View/**/*.php'
   ],
   
}