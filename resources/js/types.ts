export type School={id:number;name:string;primary_color:string;locale:string;timezone:string;currency:string};
export type Shared={auth:{user:{id:number;name:string;email:string;role:string}};school:School;flash:{success?:string};demo:boolean;logoUrl:string|null;[key:string]:unknown};
export type Student={id:number;first_name:string;last_name:string;admission_number:string;status:string};
export type OrganizationRecord={id:number;name:string;code:string};
