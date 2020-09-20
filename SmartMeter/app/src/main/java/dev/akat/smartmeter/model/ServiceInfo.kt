package dev.akat.smartmeter.model

import com.google.gson.annotations.SerializedName

data class ServiceInfo(
    @field:SerializedName("service")
    val service: Service,
    @field:SerializedName("unit")
    val unit: Unit,
    @field:SerializedName("tariff")
    val tariff: Tariff,
    @field:SerializedName("portion")
    val portion: Double,
    @field:SerializedName("coefficient")
    val coefficient: Double,
    @field:SerializedName("calculation")
    val calculation: Calculation,
    @field:SerializedName("summa")
    val summa: Double,
    @field:SerializedName("normative")
    val normative: Double = 0.0,
    @field:SerializedName("volume")
    val volume: Double = 0.0,
    @field:SerializedName("indications")
    val indications: Double = 0.0,
    @field:SerializedName("common_indications")
    val commonIndications: Double = 0.0,
    @field:SerializedName("total_indications")
    val totalIndications: Double = 0.0,
    @field:SerializedName("shared_indications")
    val sharedIndications: Double = 0.0,
    @field:SerializedName("objectSquare")
    val objectSquare: Double = 0.0,
    @field:SerializedName("total_square")
    val totalSquare: Double = 0.0,
    @field:SerializedName("portion_square")
    val portionSquare: Double = 0.0,
    @field:SerializedName("devices")
    val devices: List<Device> = ArrayList()
)