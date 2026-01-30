export const findNameByValueIterative = (arr, targetValue) => {
     const stack = [...arr]; 

     while (stack.length > 0) {
          const obj = stack.pop(); // Get the last element

          // Check if current object matches the target ID
          if (obj.value === targetValue) {
               return obj;
          }

          // If there are subs, add them to the stack
          if (obj.subs && obj.subs.length > 0) {
               stack.push(...obj.subs);
          }

          // If there are childs, add them to the stack
          if (obj.childs && obj.childs.length > 0) {
               stack.push(...obj.childs);
          }
     }

     return null; // Return null if not found
}