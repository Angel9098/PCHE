<template>
  <div class="w-100 d-flex justify-content-center">
    <div class="col-9 mt-3">
      <h1 class="text-center">Registro de Empleado</h1>
      <div class="card mt-3 mb-3 borderCircle bg-white">
        <div class="card-body">
          <form @submit.prevent="registrar">
            <h4 class="alert-heading">Datos generales</h4>
            <hr />
            <div class="d-flex flex-row justify-content-between gap-2">
              <div class="form-group col-5">
                <label for="name">Nombres</label>
                <input
                  v-model="$v.usuario.nombres.$model"
                  type="text"
                  id="nombres"
                  class="form-control"
                  required
                />
                <span v-if="$v.usuario.nombres.$error" class="text-danger">
                  Este campo es obligatorio
                </span>
              </div>
              <div class="form-group col-6">
                <label for="last_name">Apellidos</label>
                <input
                  id="last_name"
                  type="text"
                  class="form-control"
                  v-model="$v.usuario.apellidos.$model"
                  required
                />
                <span v-if="$v.usuario.apellidos.$error" class="text-danger">
                  Este campo es obligatorio
                </span>
              </div>
            </div>
            <div class="d-flex flex-row justify-content-between gap-2">
              <div class="form-group col-5">
                <label for="job_title">DUI</label>
                <input
                  id="job_title"
                  type="text"
                  class="form-control"
                  v-model="$v.usuario.dui.$model"
                  required
                  v-mask="'########-#'"
                  @change="verifyDui"
                />
                <span v-if="$v.usuario.dui.$error" class="text-danger">
                  Este campo es obligatorio
                </span>
                <span v-if="verifyDuiEmailorBoolean" class="text-danger">
                  {{ MSErrorDui }}
                </span>
              </div>
              <div class="form-group col-6">
                <label for="email">Correo Electr&#243;nico</label>
                <input
                  id="email"
                  type="email"
                  class="form-control"
                  v-model="$v.usuario.email.$model"
                  required
                  @change="verifyDomain"
                />
                <span v-if="verifyEmailExisteBoolean" class="text-danger my-3">
                  {{ MSErrorEmail }}
                </span>
                <span v-if="verifyEmailorDuiBoolean" class="text-danger">
                  {{ MSErrorEmailODui }}
                </span>
                <span v-if="$v.usuario.email.$error" class="text-danger">
                  Este campo es obligatorio
                </span>
              </div>
            </div>
            <h4 class="alert-heading pt-4">
              En caso de emergencia contactar a
            </h4>
            <hr />
            <div class="d-flex flex-row justify-content-between gap-2 mt-2">
              <div class="form-group col-5">
                <label for="nameEmergency">Nombre completo</label>
                <input
                  id="nameEmergency"
                  type="text"
                  class="form-control"
                  v-model="$v.usuario.avisar_contacto.$model"
                  required
                />
                <span
                  v-if="$v.usuario.avisar_contacto.$error"
                  class="text-danger"
                >
                  Este campo es obligatorio
                </span>
              </div>
              <div class="form-group col-6">
                <label for="phoneEmergency">Tel&#233;fono</label>
                <input
                  id="phoneEmergency"
                  type="text"
                  class="form-control"
                  v-model="$v.usuario.numero_emergencia.$model"
                  required
                  v-mask="'####-####'"
                />
                <span
                  v-if="$v.usuario.numero_emergencia.$error"
                  class="text-danger"
                >
                  Este campo es obligatorio
                </span>
              </div>
            </div>

            <h4 class="alert-heading pt-4">Empresa</h4>
            <hr />
            <div class="d-flex flex-row justify-content-between gap-2">
              <div class="form-group col-5">
                <label for="company">Empresa</label>
                <select
                  id="company"
                  class="form-select"
                  v-model="$v.usuario.empresa.$model"
                  required
                  @change="getAreas"
                >
                  <option
                    v-for="empresa in empresas"
                    :key="empresa.id"
                    :value="empresa.id"
                  >
                    {{ empresa.nombre }}
                  </option>
                </select>
                <span v-if="$v.usuario.empresa.$error" class="text-danger">
                  Este campo es obligatorio
                </span>
              </div>
              <div class="form-group col-6">
                <label for="area">&#193;rea</label>
                <select
                  id="area"
                  class="form-select"
                  v-model="$v.usuario.area_id.$model"
                  @change="getHorarios"
                  required
                  :disabled="usuario.empresa == ''"
                >
                  <option v-if="areas.length === 0" :disabled="true">
                    Sin elementos disponibles
                  </option>
                  <option v-for="a in areas" :key="a.id" :value="a.id">
                    {{ a.nombre}}
                  </option>
                </select>
                <span v-if="$v.usuario.area_id.$error" class="text-danger">
                  Este campo es obligatorio
                </span>
              </div>
            </div>
            <div class="d-flex flex-row justify-content-between">
              <div class="form-group col-5">
                <label for="job_title">Cargo</label>
                <input
                  id="job_title"
                  type="text"
                  class="form-control"
                  v-model="$v.usuario.cargo.$model"
                  required
                />
                <span v-if="$v.usuario.cargo.$error" class="text-danger">
                  Este campo es obligatorio
                </span>
              </div>
              <div class="form-group col-6">
                <label for="salary">Salario</label>
                <input
                  id="salary"
                  type="text"
                  class="form-control"
                  v-model="$v.usuario.salario.$model"
                  required
                />
                <span v-if="$v.usuario.salario.$error" class="text-danger">
                  Este campo es obligatorio
                </span>
              </div>
            </div>

            <h4 class="alert-heading pt-4">Selecci&#243;n horario</h4>
            <hr />
            <div class="d-flex flex-row justify-content-start">
              <div class="form-group col-4">
                <label for="hora">Turno</label>
                <select
                  id="hora"
                  class="form-select"
                  v-model="turno"
                  required
                  :placeholder="'Seleccione turno'"
                  @change="filterHorario"
                >
                  <option :value="'all'">Todos</option>
                  <option :value="'diurno'">Diurno</option>
                  <option :value="'nocturno'">Nocturno</option>
                </select>
              </div>
            </div>
            <div class="d-flex flex-row col-12 mt-2">
              <table class="table table-hover table-sm table-bordered">
                <thead class="text-center table-primary">
                  <tr>
                    <th scope="col">Selecci&#243;n</th>
                    <th scope="col">Horario</th>
                    <th scope="col">Horas por semana</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="horario in horariosFilter" :key="horario.id">
                    <th scope="row">
                      <div
                        class="form-check d-flex flex-row justify-content-center"
                      >
                        <input
                          class="form-check-input position-static"
                          type="checkbox"
                          id="blankCheckbox"
                          :value="horario.id"
                          aria-label="..."
                          @change="selectHorario(horario.id)"
                          :checked="horario.id === usuario.horario_id"
                        />
                      </div>
                    </th>
                    <td>{{ horario.descripcion }}</td>
                    <td class="text-center">
                      {{ horario.horas_semana }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div
              class="form-group col-12 d-flex flex-row justify-content-start"
            >
              <button
                type="submit"
                :disabled="$v.$invalid"
                class="btn btn-primary"
              >
                Guardar
              </button>
              <button
                type="button"
                class="btn btn-light mx-3"
                @click="cancelar"
              >
                Cancelar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<style>
.btn-primary {
}
</style>
<script>
import axios from "axios";
import { required, email } from "vuelidate/lib/validators";

export default {
  props: ["id"],
  data() {
    return {
      MSErrorDui: "",
      MSErrorEmailODui: "",
      MSErrorEmail:
        "Por favor, ten en cuenta que solo se permiten los siguientes dominios para esta aplicación: example@latmobile.com, example@sdsalgroup.com. Asegúrate de utilizar uno de estos dominios al registrar o ingresar.",
      usuario: {
        nombres: "",
        apellidos: "",
        email: "",
        area_id: 1,
        cargo: "",
        empresa: "",
        dui: "",
        id: "",
        horario_id: 0,
        avisar_contacto: "",
        numero_emergencia: "",
        salario: 0,
      },
      empresas: [],
      areas: [],
      horarios: [],
      horariosFilter: [],
      turno: "all",
      verifyEmailBoolean: false,
      verifyEmailorDuiBoolean: false,
      verifyDuiEmailorBoolean: false,
      verifyEmailExisteBoolean: false,
    };
  },
  validations: {
    usuario: {
      nombres: { required },
      apellidos: { required },
      email: { required, email },
      area_id: { required },
      cargo: { required },
      empresa: { required },
      dui: { required },
      horario_id: { required },
      avisar_contacto: { required },
      numero_emergencia: { required },
      salario: { required },
      // Agrega aquí las validaciones para los demás campos del objeto 'usuario' si es necesario.
    },
  },
  mounted() {
    this.getEmpresas();
    this.getHorarios();
    this.userId(this.id);
    document.title = `PCHE - Registro de usuario`;
  },
  methods: {
    registrar() {
      if (this.$v.usuario.$invalid) {
        // Formulario no válido, no se envía.
        return;
      } else {
        if (this.usuario.id != "") {
          axios
            .post("empleados/actualizar", this.usuario, {
              headers: { "Content-type": "application/json" },
            })
            .then((response) => {
              this.$toast.success("Empleado actualizado con exito", {
                timeout: 3000,
                position: "top-right",
                icon: true,
              });
              this.$router.push("/empleados");
            });
        } else {
          axios
            .post("empleados/crear", this.usuario, {
              headers: { "Content-type": "application/json" },
            })
            .then(async (response) => {
              await this.$toast.success(response.data.message, {
                timeout: 3000,
                position: "top-right",
                icon: true,
              });
              await this.$router.push("/empleados");
            });
        }
      }
    },
    verifyDui() {
      console.log(this.usuario.dui);
      this.verifyDuiAsync(this.usuario.dui);
    },
    verifyDomain() {
      const dominio = this.usuario.email.split("@")[1];
      const regex = /^(latmobile\.com|sdsalgroup\.com)$/;
      if (regex.test(dominio)) {
        this.verifyEmailBoolean = false;
        this.verifyEmail(this.usuario.email);
      } else {
        this.verifyEmailBoolean = true;
      }
    },
    getEmpresas() {
      axios
        .get("empresas", {
          headers: { "Content-type": "application/json" },
        })
        .then((resp) => {
          this.empresas = resp.data;
        });
    },
    getAreas() {
      axios
        .get(`empresa/areas?id=${this.usuario.empresa}`, {
          headers: { "Content-type": "application/json" },
        })
        .then((resp) => {
          this.areas = resp.data;
        });
    },
    getHorarios() {
      axios
        .get(`horarios`, {
          headers: { "Content-type": "application/json" },
        })
        .then((resp) => {
          this.horarios = resp.data;
          this.horariosFilter = resp.data;
        });
    },
    userId(id) {
      if (id != 0) {
        axios
          .get(`empleadobyid?idEmpleado=${id}`)
          .then((result) => {
            console.log(result.data[0].area.empresa.nombre);
            if (result.data && result.data.length > 0) {
              this.usuario.nombres = result.data[0].nombres;
              this.usuario.empresa = result.data[0].area.empresa.id;
              this.usuario.area_id = result.data[0].area.id;
              this.usuario.apellidos = result.data[0].apellidos;
              this.usuario.email = result.data[0].email;
              this.usuario.cargo = result.data[0].cargo;
              this.usuario.dui = result.data[0].dui;
              this.usuario.horario_id = result.data[0].horario_id;
              this.usuario.avisar_contacto = result.data[0].avisar_contacto;
              this.usuario.numero_emergencia = result.data[0].numero_emergencia;
              this.usuario.salario = result.data[0].salario;
              this.usuario.id = result.data[0].id;
              axios
                .get(`empresa/areas?id=${this.usuario.empresa}`, {
                  headers: { "Content-type": "application/json" },
                })
                .then((resp) => {
                    console.log(resp)
                  this.areas = resp.data;
                });
            } else {
              console.error(
                "No se encontraron datos para el empleado con ID:",
                id
              );
            }
          })
          .catch((error) => {
            console.error("Error al obtener datos del empleado:", error);
          });
      }
    },
    filterHorario() {
      if (this.turno == "all") {
        this.getHorarios();
      } else {
        this.horariosFilter = [];
        this.horariosFilter = this.horarios.filter(
          (item) =>
            this.turno.localeCompare(item.turno, "es", {
              sensitivity: "accent",
            }) == 0
        );
      }
    },
    selectHorario(id) {
      this.usuario.horario_id = id;
    },
    cancelar() {
      this.$confirm("¿Estás seguro de que deseas salir de esta pantalla?").then(
        () => {
          if (!this.$v.usuario.$invalid) {
            // Formulario no válido, no se envía.
            return;
          } else {
            this.usuario.nombres = "";
            this.usuario.apellidos = "";
            this.usuario.email = "";
            this.usuario.area_id = 0;
            this.usuario.cargo = "";
            this.usuario.empresa = "";
            this.usuario.dui = "";
            this.usuario.horario_id = 0;
            this.usuario.avisar_contacto = "";
            this.usuario.numero_emergencia = "";
            this.usuario.salario = 0;
            this.$router.push("/dashboard");
          }
        }
      );
    },
    async verifyDuiAsync(dui) {
      const url = `empleados/existeDui?dui=${dui}`;
      try {
        const response = await fetch(url);
        if (!response.ok) {
          this.verifyDuiEmailorBoolean = true;
          const data = await response.json();
          console.log(data.message);
          this.MSErrorDui =
            data.message === undefined || data.message === ""
              ? "El dui ya está en uso"
              : data.message;
        } else {
          this.verifyDuiEmailorBoolean = false;
        }
      } catch (error) {
        this.verifyDuiEmailorBoolean = true;
        this.MSErrorDui = "No he logrado verificar si el DUI existe o no.";
        console.error("No he logrado verificar si el DUI existe o no.");
      }
    },
    async verifyEmail(Email) {
      const url = `empleados/existeEmail?email=${Email}`;
      try {
        const response = await fetch(url);
        if (!response.ok) {
          const data = await response.json();
          this.verifyEmailorDuiBoolean = true;
          this.MSErrorEmailODui =
            data.message === undefined || data.message === ""
              ? "El correo electrónico ya está en uso"
              : data.message;
          console.log(data);
        } else {
          this.verifyEmailorDuiBoolean = false;
        }
      } catch (error) {
        this.verifyEmailorDuiBoolean = true;
        this.MSErrorEmailODui =
          "No he logrado verificar si el Email existe o no.";
        console.error("Error al realizar la solicitud fetch:", error);
      }
    },
  },
};
</script>
<style>
.borderCircle {
  border-radius: 20px;
}
</style>
