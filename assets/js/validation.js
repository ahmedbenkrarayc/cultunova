window.validateForm = (inputs) => {
    const errors = []
  
    inputs.forEach((input) => {
      const { type, value, name, minChars, range } = input
      console.log(type, value, name, minChars, range)
  
      if(type === "email") {
        const emailRegex = /\S+@\S+\.\S+/
        if (!emailRegex.test(value)){
          errors.push(`${name} must be a valid email.`)
        }
      }else if(type === "string"){
          if (typeof value != "string" || value.trim() === "") {
              errors.push(`${name} must be a non-empty string.`)
            }else if(minChars && value.trim().length < minChars) {
          errors.push(`${name} must have at least ${minChars} characters.`)
        }
      }else if(type === "number"){
        if (isNaN(value)) {
          errors.push(`${name} must be a valid number.`)
        }
      }else if(type === 'range'){
        if(!range.includes(value.toLowerCase())){
            errors.push(`${name} must be one of these values ${range.join(', ')}.`)
        }
      }
    })
  
    return errors.length ? errors : null
}