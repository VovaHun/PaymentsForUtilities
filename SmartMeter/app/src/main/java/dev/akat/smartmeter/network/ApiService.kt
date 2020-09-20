package dev.akat.smartmeter.network

import dev.akat.smartmeter.network.responses.*
import retrofit2.http.GET
import retrofit2.http.Query

interface ApiService {

    @GET("/api/mobile.php?method=authorization")
    suspend fun authRequest(
        @Query("login") login: String,
        @Query("password") password: String
    ): AuthResponse

    @GET("/api/mobile.php?method=updateUser")
    suspend fun updateUser(
        @Query("login") login: String,
        @Query("password") password: String,
        @Query("name") name: String,
        @Query("gender") gender: Int,
        @Query("email") email: String,
        @Query("email_notifications") emailNotifications: Int,
        @Query("phone") phone: String,
        @Query("phone_notifications") phoneNotifications: Int,
        @Query("comment") comment: String,
        @Query("consent_on_personal_data") consent: Int
    ): AuthResponse

    @GET("/api/mobile.php?method=getPersonalAccounts")
    suspend fun getPersonalAccounts(
        @Query("id") id: Long
    ): AccountListResponse

    @GET("/api/mobile.php?method=getCompanyList")
    suspend fun getCompanyList(): CompanyListResponse

    @GET("/api/mobile.php?method=getAccountInfo")
    suspend fun getAccountInfo(
        @Query("id") id: Long,
        @Query("account_id") accountId: Long
    ): AccountInfoResponse

    @GET("/api/mobile.php?method=updateDeviceIndications")
    suspend fun updateDeviceIndications(
        @Query("device_id") deviceId: Long,
        @Query("indications") indications: Double
    ): ApiResponse

    @GET("/api/mobile.php?method=updateAccountQuery")
    suspend fun updateAccountQuery(
        @Query("id") id: Long,
        @Query("account_name") accountName: String,
        @Query("company_id") companyId: Long
    ): ApiResponse

    @GET("/api/mobile.php?method=getQueryAccounts&status=false")
    suspend fun getQueryAccounts(
        @Query("id") id: Long
    ): QueryListResponse

}