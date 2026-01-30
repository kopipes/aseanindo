import axios from "axios"
import { useContext } from "./useContext.js"

const context = useContext()
const page = context.page

export const useRequest = () => {
     const POST = (url, data) => {
          const user = context.cookie.get('authentication')
          return axios.post(`${page.base_url}/api/${url}`, data, {
               headers: {
                    'company-code': page.company.id,
                    'Content-Type': 'multipart/form-data',
                    // 'user-account' : user ? user.id : null
               }
          })
     }

     const GET = (url) => {
          const user = context.cookie.get('authentication')
          return axios.get(`${page.base_url}/api/${url}`, {
               headers: {
                    'company-code': page.company.id,
                    // 'user-account' : user ? user.id : null
               }
          })
     }

     const FETCH_CSAT_CONTENT = (ticketId) => {
          context.cookie.del('CSAT-TEMPLATE')
          GET(`chat/message/${ticketId}/csat-template`)
               .then((result)=>{
                    if(result.status==200){
                         context.cookie.set('CSAT-TEMPLATE',result.data)
                         window.dispatchEvent(new Event('CSAT_TEMPLATE'));
                    }
               }).catch((err)=>{
                    window.dispatchEvent(new Event('CSAT_TEMPLATE'));
               })
     }

     return {
          POST,
          GET,
          FETCH_CSAT_CONTENT
     }
}