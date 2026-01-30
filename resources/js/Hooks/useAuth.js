import { useRequest } from "./useRequest"
import { useContext } from "./useContext"
import { api } from "../Service/api-service"
import { joinBroadcast } from "../socket"
const defaultAvatar = 'https://yelow-app-storage.s3.ap-southeast-1.amazonaws.com/cnako525c6GTGkUq1nefIJ38mXinpV5JovDMuuws.png'

const request = useRequest()
const context = useContext()
export const useAuth = () => {

     const user = context.cookie.get('authentication')

     const name = user ? user.name : 'Guest'
     const profile = user ? user.profile : defaultAvatar
     const user_id = user ? user.id : null
     const regid = user ? user.token : null

     const login = (data, callback, error) => {
          return request.POST('auth/login', data)
               .then((result) => {
                    const user = result.data
                    callback()
                    // context.cookie.set('authentication', user)
                    // joinBroadcast(user.id)
                    // request callback
                    api.postRequestCallCallback({
                         helpdesk_id : data.helpdesk_id,
                         user_id : user.id,
                         product_id : data.product_id,
                         note : data.note,
                         name : data.name,
                         email : data.email,
                         phone_code : data.phone_code,
                         phone : data.phone,
                    })

               })
               .catch((err) => {
                    error()
               })
     }

     return {
          name,
          profile,
          user_id,
          regid,
          login
     }
}